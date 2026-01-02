<?php

declare(strict_types=1);

namespace App\Modules\Biometric\Presentation\Http\Controllers;

use App\Modules\Biometric\Application\Services\BiometricService;
use App\Models\Biometric;
use App\Modules\Biometric\Presentation\Http\Requests\CreateBiometricRequest;
use App\Modules\Biometric\Presentation\Http\Requests\IndexBiometricRequest;
use App\Modules\Biometric\Presentation\Http\Requests\UpdateBiometricRequest;
use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BiometricController extends ApiController
{
    public function __construct(
        private readonly BiometricService $service
    ) {
    }

    public function index(IndexBiometricRequest $request): JsonResponse
    {
        return $this->success($this->service->findAll($request->toDTO()));
    }

    public function show(Biometric $biometric): JsonResponse
    {
        if ($biometric->user_id !== auth()->id()) {
            return $this->error('Biometric not found', Response::HTTP_NOT_FOUND);
        }
        return $this->success($this->service->findById((string) $biometric->id, auth()->id()));
    }

    public function store(CreateBiometricRequest $request): JsonResponse
    {
        return $this->success($this->service->create($request->toDTO()), 'Biometric created successfully', Response::HTTP_CREATED);
    }

    public function update(UpdateBiometricRequest $request, Biometric $biometric): JsonResponse
    {
        if ($biometric->user_id !== auth()->id()) {
            return $this->error('Biometric not found', Response::HTTP_NOT_FOUND);
        }
        return $this->success($this->service->update($request->toDTO(), auth()->id()), 'Biometric updated successfully');
    }

    public function destroy(Biometric $biometric): JsonResponse
    {
        if ($biometric->user_id !== auth()->id()) {
            return $this->error('Biometric not found', Response::HTTP_NOT_FOUND);
        }
        return $this->success($this->service->delete((string) $biometric->id, auth()->id()), 'Biometric deleted successfully', Response::HTTP_NO_CONTENT);
    }
}

