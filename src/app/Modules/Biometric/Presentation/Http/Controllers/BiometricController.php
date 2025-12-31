<?php

declare(strict_types=1);

namespace App\Modules\Biometric\Presentation\Http\Controllers;

use App\Modules\Biometric\Application\Services\BiometricService;
use App\Modules\Biometric\Domain\Exceptions\BiometricNotFoundException;
use App\Models\Biometric;
use App\Modules\Biometric\Presentation\Http\Requests\CreateBiometricRequest;
use App\Modules\Biometric\Presentation\Http\Requests\IndexBiometricRequest;
use App\Modules\Biometric\Presentation\Http\Requests\UpdateBiometricRequest;
use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class BiometricController extends ApiController
{
    public function __construct(
        private readonly BiometricService $service
    ) {
    }

    public function index(IndexBiometricRequest $request): JsonResponse
    {
        $results = $this->service->findAll($request->toDTO());

        return $this->success($results);
    }

    public function show(Biometric $biometric): JsonResponse
    {
        try {
            $biometricData = $this->service->findById((string) $biometric->id);
            return $this->success($biometricData);
        } catch (BiometricNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        }
    }

    public function store(CreateBiometricRequest $request): JsonResponse
    {
        try {
            $biometric = $this->service->create($request->toDTO());
            return $this->success($biometric, 'Biometric created successfully', Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return $this->error('Failed to create biometric', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateBiometricRequest $request, Biometric $biometric): JsonResponse
    {
        try {
            $updatedBiometric = $this->service->update($request->toDTO());
            return $this->success($updatedBiometric, 'Biometric updated successfully');
        } catch (BiometricNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to update biometric', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Biometric $biometric): JsonResponse
    {
        try {
            $this->service->delete((string) $biometric->id);
            return $this->success(null, 'Biometric deleted successfully', Response::HTTP_NO_CONTENT);
        } catch (BiometricNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to delete biometric', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

