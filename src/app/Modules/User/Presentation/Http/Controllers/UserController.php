<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\Http\Controllers;

use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use App\Modules\User\Application\Services\UserService;
use App\Models\User;
use App\Modules\User\Presentation\Http\Requests\CreateUserRequest;
use App\Modules\User\Presentation\Http\Requests\IndexUserRequest;
use App\Modules\User\Presentation\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends ApiController
{
    public function __construct(
        private readonly UserService $service
    ) {
    }

    public function index(IndexUserRequest $request): JsonResponse
    {
        return $this->success($this->service->findAll($request->toDTO()));
    }

    public function show(User $user): JsonResponse
    {
        return $this->success($this->service->findById((string) $user->id));
    }

    public function store(CreateUserRequest $request): JsonResponse
    {
        return $this->success($this->service->create($request->toDTO()), 'User created successfully', Response::HTTP_CREATED);
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        return $this->success($this->service->update($request->toDTO()), 'User updated successfully');
    }

    public function destroy(User $user): JsonResponse
    {
        return $this->success($this->service->delete((string) $user->id));
    }
}

