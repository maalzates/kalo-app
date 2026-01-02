<?php

declare(strict_types=1);

namespace App\Modules\Permission\Infrastructure\Repositories;

use App\Modules\Permission\Domain\Contracts\PermissionRepositoryInterface;
use App\Modules\Permission\Domain\Exceptions\PermissionCreationFailedException;
use App\Modules\Permission\Domain\Exceptions\PermissionUpdateFailedException;
use App\Models\Permission;
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
        } catch (Throwable $exception) {
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
            return ($permission = Permission::with('roles')->find($id)) ? $permission->toArray() : null;
        } catch (Throwable $exception) {
            return null;
        }
    }

    public function create(array $data): array
    {
        try {
            return Permission::create($data)->toArray();
        } catch (Throwable $exception) {
            throw PermissionCreationFailedException::fromException($exception);
        }
    }

    public function update(string $id, array $data): bool
    {
        try {
            return Permission::findOrFail($id)->update($data);
        } catch (Throwable $exception) {
            throw PermissionUpdateFailedException::fromException($id, $exception);
        }
    }

    public function delete(string $id): bool
    {
        try {
            return Permission::findOrFail($id)->delete();
        } catch (Throwable $exception) {
            return false;
        }
    }

    public function hasRoles(string $permissionId): bool
    {
        try {
            return Permission::findOrFail($permissionId)->roles()->count() > 0;
        } catch (Throwable $exception) {
            return false;
        }
    }
}

