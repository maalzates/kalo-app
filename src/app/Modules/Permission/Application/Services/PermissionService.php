<?php

declare(strict_types=1);

namespace App\Modules\Permission\Application\Services;

use App\Modules\Permission\Application\DTOs\CreatePermissionDTO;
use App\Modules\Permission\Application\DTOs\PermissionFilterDTO;
use App\Modules\Permission\Application\DTOs\UpdatePermissionDTO;
use App\Modules\Permission\Domain\Contracts\PermissionRepositoryInterface;
use App\Modules\Permission\Domain\Exceptions\PermissionInUseException;
use App\Modules\Permission\Domain\Exceptions\PermissionNotFoundException;

class PermissionService
{
    public function __construct(
        private readonly PermissionRepositoryInterface $repository
    ) {
    }

    public function findAll(PermissionFilterDTO $filters): array
    {
        return $this->repository->findAll([
            'search' => $filters->search,
            'page' => $filters->page,
            'perPage' => $filters->perPage,
        ]);
    }

    public function findById(string $id): array
    {
        $permission = $this->repository->findById($id);

        if ($permission === null) {
            throw PermissionNotFoundException::withId($id);
        }

        return $permission;
    }

    public function create(CreatePermissionDTO $dto): array
    {
        return $this->repository->create([
            'name' => $dto->name,
        ]);
    }

    public function update(UpdatePermissionDTO $dto): array
    {
        $permission = $this->repository->findById($dto->permissionId);

        if ($permission === null) {
            throw PermissionNotFoundException::withId($dto->permissionId);
        }

        $updateData = array_filter([
            'name' => $dto->name,
        ], fn ($value) => $value !== null);

        if (count($updateData) > 0) {
            $this->repository->update($dto->permissionId, $updateData);
        }

        return $this->repository->findById($dto->permissionId);
    }

    public function delete(string $id): bool
    {
        $permission = $this->repository->findById($id);

        if ($permission === null) {
            throw PermissionNotFoundException::withId($id);
        }

        if ($this->repository->hasRoles($id)) {
            throw PermissionInUseException::withId($id);
        }

        return $this->repository->delete($id);
    }
}

