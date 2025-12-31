<?php

declare(strict_types=1);

namespace App\Modules\Role\Domain\Contracts;

interface RoleRepositoryInterface
{
    public function findAll(array $filters): array;

    public function findById(string $id): ?array;

    public function create(array $data): array;

    public function update(string $id, array $data): bool;

    public function delete(string $id): bool;

    public function attachPermission(string $roleId, string $permissionId): bool;

    public function detachPermission(string $roleId, string $permissionId): bool;

    public function hasUsers(string $roleId): bool;
}

