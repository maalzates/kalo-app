<?php

declare(strict_types=1);

namespace App\Modules\Macro\Presentation\Http\Controllers;

use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use App\Modules\Macro\Application\Services\MacroService;
use App\Modules\Macro\Domain\Exceptions\DuplicateMacroException;
use App\Modules\Macro\Domain\Exceptions\MacroNotFoundException;
use App\Models\Macro;
use App\Modules\Macro\Presentation\Http\Requests\CreateMacroRequest;
use App\Modules\Macro\Presentation\Http\Requests\IndexMacroRequest;
use App\Modules\Macro\Presentation\Http\Requests\UpdateMacroRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class MacroController extends ApiController
{
    public function __construct(
        private readonly MacroService $service
    ) {
    }

    public function index(IndexMacroRequest $request): JsonResponse
    {
        $results = $this->service->findAll($request->toDTO());

        return $this->success($results);
    }

    public function show(Macro $macro): JsonResponse
    {
        try {
            $macroData = $this->service->findById((string) $macro->id);
            return $this->success($macroData);
        } catch (MacroNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        }
    }

    public function store(CreateMacroRequest $request): JsonResponse
    {
        try {
            $macro = $this->service->create($request->toDTO());
            return $this->success($macro, 'Macro created successfully', Response::HTTP_CREATED);
        } catch (DuplicateMacroException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to create macro', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateMacroRequest $request, Macro $macro): JsonResponse
    {
        try {
            $updatedMacro = $this->service->update($request->toDTO());
            return $this->success($updatedMacro, 'Macro updated successfully');
        } catch (MacroNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to update macro', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Macro $macro): JsonResponse
    {
        try {
            $this->service->delete((string) $macro->id);
            return $this->success(null, 'Macro deleted successfully', Response::HTTP_NO_CONTENT);
        } catch (MacroNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to delete macro', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

