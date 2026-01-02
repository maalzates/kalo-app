<?php

declare(strict_types=1);

namespace App\Modules\Macro\Presentation\Http\Controllers;

use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use App\Modules\Macro\Application\Services\MacroService;
use App\Models\Macro;
use App\Modules\Macro\Presentation\Http\Requests\CreateMacroRequest;
use App\Modules\Macro\Presentation\Http\Requests\IndexMacroRequest;
use App\Modules\Macro\Presentation\Http\Requests\UpdateMacroRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class MacroController extends ApiController
{
    public function __construct(
        private readonly MacroService $service
    ) {
    }

    public function index(IndexMacroRequest $request): JsonResponse
    {
        return $this->success($this->service->findAll($request->toDTO()));
    }

    public function show(Macro $macro): JsonResponse
    {
        if ($macro->user_id !== auth()->id()) {
            return $this->error('Macro not found', Response::HTTP_NOT_FOUND);
        }
        return $this->success($this->service->findById((string) $macro->id, auth()->id()));
    }

    public function store(CreateMacroRequest $request): JsonResponse
    {
        return $this->success($this->service->create($request->toDTO()), 'Macro created successfully', Response::HTTP_CREATED);
    }

    public function update(UpdateMacroRequest $request, Macro $macro): JsonResponse
    {
        if ($macro->user_id !== auth()->id()) {
            return $this->error('Macro not found', Response::HTTP_NOT_FOUND);
        }
        return $this->success($this->service->update($request->toDTO(), auth()->id()), 'Macro updated successfully');
    }

    public function destroy(Macro $macro): JsonResponse
    {
        if ($macro->user_id !== auth()->id()) {
            return $this->error('Macro not found', Response::HTTP_NOT_FOUND);
        }
        return $this->success($this->service->delete((string) $macro->id, auth()->id()), 'Macro deleted successfully', Response::HTTP_NO_CONTENT);
    }
}

