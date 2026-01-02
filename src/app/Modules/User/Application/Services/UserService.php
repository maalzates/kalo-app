<?php

declare(strict_types=1);

namespace App\Modules\User\Application\Services;

use App\Modules\User\Application\DTOs\CreateUserDTO;
use App\Modules\User\Application\DTOs\UpdateUserDTO;
use App\Modules\User\Application\DTOs\UserFilterDTO;
use App\Modules\User\Domain\Contracts\UserRepositoryInterface;
use App\Modules\User\Domain\Exceptions\UserNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
            'role_id' => $dto->roleId ?? '1',
        ]);
    }

    public function update(UpdateUserDTO $dto): array
    {
        $user = $this->repository->findById($dto->userId);

        if ($user === null) {
            throw UserNotFoundException::withId($dto->userId);
        }

        // Validar y actualizar contraseña si se proporciona
        if ($dto->newPassword) {
            if (!$dto->currentPassword || !$this->repository->validatePassword($dto->userId, $dto->currentPassword)) {
                throw ValidationException::withMessages([
                    'current_password' => ['The current password is incorrect.'],
                ]);
            }
        }

        $updateData = array_filter([
            'name' => $dto->name,
            'email' => $dto->email,
            'phone' => $dto->phone,
            'birth_date' => $dto->birthDate,
            'gender' => $dto->gender,
            'height' => $dto->height,
            'weight' => $dto->weight,
            'role_id' => $dto->roleId,
            'country_code' => $dto->countryCode,
            'activity_level' => $dto->activityLevel,
            'goal_type' => $dto->goalType,
            'macro_calculation_mode' => $dto->macroCalculationMode,
            'password' => $dto->newPassword ? Hash::make($dto->newPassword) : null,
        ], fn ($value) => $value !== null);

        $this->repository->update($dto->userId, $updateData);

        // Recargar usuario con relaciones desde el repositorio
        $updatedUser = $this->repository->findByIdWithRelations($dto->userId);
        
        if ($updatedUser === null) {
            throw UserNotFoundException::withId($dto->userId);
        }

        return $updatedUser;
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

