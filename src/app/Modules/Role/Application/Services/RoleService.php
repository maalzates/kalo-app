<?php

declare(strict_types=1);

namespace App\Modules\Role\Application\Services;

use App\Modules\Role\Application\DTOs\AttachPermissionDTO;
use App\Modules\Role\Application\DTOs\CreateRoleDTO;
use App\Modules\Role\Application\DTOs\RoleFilterDTO;
use App\Modules\Role\Application\DTOs\UpdateRoleDTO;
use App\Modules\Role\Domain\Contracts\RoleRepositoryInterface;
use App\Modules\Role\Domain\Exceptions\RoleInUseException;
use App\Modules\Role\Domain\Exceptions\RoleNotFoundException;
use Throwable;

class RoleService
{
    public function __construct(
        private readonly RoleRepositoryInterface $repository
    ) {
    }

    public function findAll(RoleFilterDTO $filters): array
    {
        return $this->repository->findAll([
            'search' => $filters->search,
            'page' => $filters->page,
            'perPage' => $filters->perPage,
        ]);
    }

    public function findById(string $id): array
    {
        $role = $this->repository->findById($id);

        if ($role === null) {
            throw RoleNotFoundException::withId($id);
        }

        return $role;
    }

    public function create(CreateRoleDTO $dto): array
    {
        return $this->repository->create([
            'name' => $dto->name,
            'permission_ids' => $dto->permissionIds,
        ]);
    }

    public function update(UpdateRoleDTO $dto): array
    {
        $role = $this->repository->findById($dto->roleId);

        if ($role === null) {
            throw RoleNotFoundException::withId($dto->roleId);
        }

        $updateData = array_filter([
            'name' => $dto->name,
        ], fn ($value) => $value !== null);

        if (count($updateData) > 0) {
            $this->repository->update($dto->roleId, $updateData);
        }

        return $this->repository->findById($dto->roleId);
    }

    public function delete(string $id): bool
    {
        $role = $this->repository->findById($id);

        if ($role === null) {
            throw RoleNotFoundException::withId($id);
        }

        if ($this->repository->hasUsers($id)) {
            throw RoleInUseException::withId($id);
        }

        return $this->repository->delete($id);
    }

    public function attachPermission(AttachPermissionDTO $dto): bool
    {
        $role = $this->repository->findById($dto->roleId);

        if ($role === null) {
            throw RoleNotFoundException::withId($dto->roleId);
        }

        return $this->repository->attachPermission($dto->roleId, $dto->permissionId);
    }

    public function detachPermission(AttachPermissionDTO $dto): bool
    {
        $role = $this->repository->findById($dto->roleId);

        if ($role === null) {
            throw RoleNotFoundException::withId($dto->roleId);
        }

        return $this->repository->detachPermission($dto->roleId, $dto->permissionId);
    }
}

