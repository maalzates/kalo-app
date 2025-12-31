<?php

declare(strict_types=1);

namespace App\Modules\Permission\Infrastructure\Repositories;

use App\Modules\Permission\Domain\Contracts\PermissionRepositoryInterface;
use App\Modules\Permission\Domain\Exceptions\PermissionCreationFailedException;
use App\Modules\Permission\Domain\Exceptions\PermissionUpdateFailedException;
use App\Modules\Permission\Infrastructure\Models\Permission;
use Throwable;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function findAll(array $filters): array
    {
        try {
            $query = Permission::query();

            if (isset($filters['search']) && $filters['search'] !== null) {
                $search = $filters['search'];
                $query->where('name', 'like', "%{$search}%");
            }

            $page = $filters['page'] ?? 1;
            $perPage = $filters['perPage'] ?? 15;

            $paginated = $query->with('roles')->paginate($perPage, ['*'], 'page', $page);

            return [
                'data' => $paginated->items(),
                'meta' => [
                    'current_page' => $paginated->currentPage(),
                    'per_page' => $paginated->perPage(),
                    'total' => $paginated->total(),
                ],
            ];
        } catch (Throwable $e) {
            return [
                'data' => [],
                'meta' => [
                    'current_page' => 1,
                    'per_page' => 15,
                    'total' => 0,
                ],
            ];
        }
    }

    public function findById(string $id): ?array
    {
        try {
            $permission = Permission::with('roles')->find($id);
            return $permission ? $permission->toArray() : null;
        } catch (Throwable $e) {
            return null;
        }
    }

    public function create(array $data): array
    {
        try {
            $permission = Permission::create($data);
            return $permission->toArray();
        } catch (Throwable $e) {
            throw PermissionCreationFailedException::fromException($e);
        }
    }

    public function update(string $id, array $data): bool
    {
        try {
            $permission = Permission::findOrFail($id);
            return $permission->update($data);
        } catch (Throwable $e) {
            throw PermissionUpdateFailedException::fromException($id, $e);
        }
    }

    public function delete(string $id): bool
    {
        try {
            $permission = Permission::findOrFail($id);
            return $permission->delete();
        } catch (Throwable $e) {
            return false;
        }
    }

    public function hasRoles(string $permissionId): bool
    {
        try {
            $permission = Permission::findOrFail($permissionId);
            return $permission->roles()->count() > 0;
        } catch (Throwable $e) {
            return false;
        }
    }
}

