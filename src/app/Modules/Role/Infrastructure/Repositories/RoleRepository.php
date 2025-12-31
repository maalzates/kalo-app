<?php

declare(strict_types=1);

namespace App\Modules\Role\Infrastructure\Repositories;

use App\Modules\Role\Domain\Contracts\RoleRepositoryInterface;
use App\Modules\Role\Domain\Exceptions\PermissionAttachmentFailedException;
use App\Modules\Role\Domain\Exceptions\RoleCreationFailedException;
use App\Modules\Role\Domain\Exceptions\RoleUpdateFailedException;
use App\Models\Role;
use Throwable;

class RoleRepository implements RoleRepositoryInterface
{
    public function findAll(array $filters): array
    {
        try {
            $query = Role::query();

            if (isset($filters['search']) && $filters['search'] !== null) {
                $search = $filters['search'];
                $query->where('name', 'like', "%{$search}%");
            }

            $page = $filters['page'] ?? 1;
            $perPage = $filters['perPage'] ?? 15;

            $paginated = $query->with('permissions')->paginate($perPage, ['*'], 'page', $page);

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
            $role = Role::with('permissions')->find($id);
            return $role ? $role->toArray() : null;
        } catch (Throwable $e) {
            return null;
        }
    }

    public function create(array $data): array
    {
        try {
            $permissionIds = $data['permission_ids'] ?? null;
            unset($data['permission_ids']);

            $role = Role::create($data);

            if ($permissionIds !== null && is_array($permissionIds) && count($permissionIds) > 0) {
                $role->permissions()->attach($permissionIds);
            }

            return $role->load('permissions')->toArray();
        } catch (Throwable $e) {
            throw RoleCreationFailedException::fromException($e);
        }
    }

    public function update(string $id, array $data): bool
    {
        try {
            $role = Role::findOrFail($id);
            return $role->update($data);
        } catch (Throwable $e) {
            throw RoleUpdateFailedException::fromException($id, $e);
        }
    }

    public function delete(string $id): bool
    {
        try {
            $role = Role::findOrFail($id);
            return $role->delete();
        } catch (Throwable $e) {
            return false;
        }
    }

    public function attachPermission(string $roleId, string $permissionId): bool
    {
        try {
            $role = Role::findOrFail($roleId);
            $role->permissions()->syncWithoutDetaching([$permissionId]);
            return true;
        } catch (Throwable $e) {
            throw PermissionAttachmentFailedException::fromException($roleId, $permissionId, $e);
        }
    }

    public function detachPermission(string $roleId, string $permissionId): bool
    {
        try {
            $role = Role::findOrFail($roleId);
            $role->permissions()->detach($permissionId);
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    public function hasUsers(string $roleId): bool
    {
        try {
            $role = Role::findOrFail($roleId);
            return $role->users()->count() > 0;
        } catch (Throwable $e) {
            return false;
        }
    }
}

