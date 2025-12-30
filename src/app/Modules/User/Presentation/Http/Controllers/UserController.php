<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\Http\Controllers;

use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use App\Modules\User\Application\Services\UserService;
use App\Modules\User\Domain\Exceptions\UserNotFoundException;
use App\Modules\User\Infrastructure\Models\User;
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
        $results = $this->service->findAll($request->toDTO());

        return $this->success($results);
    }

    public function show(User $user): JsonResponse
    {
        try {
            $userData = $this->service->findById((string) $user->id);
            return $this->success($userData);
        } catch (UserNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        }
    }

    public function store(CreateUserRequest $request): JsonResponse
    {
        try {
            $user = $this->service->create($request->toDTO());
            return $this->success($user, 'User created successfully', Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            return $this->error('Failed to create user', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        try {
            $updatedUser = $this->service->update($request->toDTO());
            return $this->success($updatedUser, 'User updated successfully');
        } catch (UserNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (\Throwable $e) {
            return $this->error('Failed to update user', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(User $user): JsonResponse
    {
        try {
            $this->service->delete((string) $user->id);
            return $this->success(null, 'User deleted successfully', Response::HTTP_NO_CONTENT);
        } catch (UserNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (\Throwable $e) {
            return $this->error('Failed to delete user', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

