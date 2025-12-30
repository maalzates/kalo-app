<?php

declare(strict_types=1);

namespace App\Modules\User\Application\Services;

use App\Modules\User\Application\DTOs\CreateUserDTO;
use App\Modules\User\Application\DTOs\UpdateUserDTO;
use App\Modules\User\Application\DTOs\UserFilterDTO;
use App\Modules\User\Domain\Contracts\UserRepositoryInterface;
use App\Modules\User\Domain\Exceptions\UserNotFoundException;
use Throwable;

class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {
    }

    public function findAll(UserFilterDTO $filters): array
    {
        return $this->repository->findAll([
            'search' => $filters->search,
            'gender' => $filters->gender,
            'page' => $filters->page,
            'perPage' => $filters->perPage,
        ]);
    }

    public function findById(string $id): array
    {
        $user = $this->repository->findById($id);

        if ($user === null) {
            throw UserNotFoundException::withId($id);
        }

        return $user;
    }

    public function create(CreateUserDTO $dto): array
    {
        return $this->repository->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'phone' => $dto->phone,
            'birth_date' => $dto->birthDate,
            'gender' => $dto->gender,
            'height' => $dto->height,
            'google_id' => $dto->googleId,
            'auth_provider' => $dto->authProvider,
            'password' => $dto->password,
        ]);
    }

    public function update(UpdateUserDTO $dto): array
    {
        $user = $this->repository->findById($dto->userId);

        if ($user === null) {
            throw UserNotFoundException::withId($dto->userId);
        }

        $updateData = array_filter([
            'name' => $dto->name,
            'email' => $dto->email,
            'phone' => $dto->phone,
            'birth_date' => $dto->birthDate,
            'gender' => $dto->gender,
            'height' => $dto->height,
        ], fn ($value) => $value !== null);

        $this->repository->update($dto->userId, $updateData);

        return $this->repository->findById($dto->userId);
    }

    public function delete(string $id): bool
    {
        $user = $this->repository->findById($id);

        if ($user === null) {
            throw UserNotFoundException::withId($id);
        }

        return $this->repository->delete($id);
    }
}

