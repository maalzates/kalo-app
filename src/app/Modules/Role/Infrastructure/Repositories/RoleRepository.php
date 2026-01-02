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
            return ($role = Role::with('permissions')->find($id)) ? $role->toArray() : null;
        } catch (Throwable $exception) {
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
        } catch (Throwable $exception) {
            throw RoleCreationFailedException::fromException($exception);
        }
    }

    public function update(string $id, array $data): bool
    {
        try {
            return Role::findOrFail($id)->update($data);
        } catch (Throwable $exception) {
            throw RoleUpdateFailedException::fromException($id, $exception);
        }
    }

    public function delete(string $id): bool
    {
        try {
            return Role::findOrFail($id)->delete();
        } catch (Throwable $exception) {
            return false;
        }
    }

    public function attachPermission(string $roleId, string $permissionId): bool
    {
        try {
            Role::findOrFail($roleId)->permissions()->syncWithoutDetaching([$permissionId]);
            return true;
        } catch (Throwable $exception) {
            throw PermissionAttachmentFailedException::fromException($roleId, $permissionId, $exception);
        }
    }

    public function detachPermission(string $roleId, string $permissionId): bool
    {
        try {
            Role::findOrFail($roleId)->permissions()->detach($permissionId);
            return true;
        } catch (Throwable $exception) {
            return false;
        }
    }

    public function hasUsers(string $roleId): bool
    {
        try {
            return Role::findOrFail($roleId)->users()->count() > 0;
        } catch (Throwable $exception) {
            return false;
        }
    }
}

