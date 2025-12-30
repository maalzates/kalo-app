<?php

declare(strict_types=1);

namespace App\Modules\User\Infrastructure\Repositories;

use App\Modules\User\Domain\Contracts\UserRepositoryInterface;
use App\Modules\User\Domain\Exceptions\UserCreationFailedException;
use App\Modules\User\Domain\Exceptions\UserUpdateFailedException;
use App\Modules\User\Infrastructure\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

class UserRepository implements UserRepositoryInterface
{
    public function findAll(array $filters): array
    {
        try {
            $query = User::query();

            if (isset($filters['search']) && $filters['search'] !== null) {
                $search = $filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if (isset($filters['gender']) && $filters['gender'] !== null) {
                $query->where('gender', $filters['gender']);
            }

            $page = $filters['page'] ?? 1;
            $perPage = $filters['perPage'] ?? 15;

            $paginated = $query->paginate($perPage, ['*'], 'page', $page);

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
            $user = User::find($id);
            return $user ? $user->toArray() : null;
        } catch (Throwable $e) {
            return null;
        }
    }

    public function findByEmail(string $email): ?array
    {
        try {
            $user = User::where('email', $email)->first();
            return $user ? $user->toArray() : null;
        } catch (Throwable $e) {
            return null;
        }
    }

    public function create(array $data): array
    {
        try {
            $user = User::create($data);
            return $user->toArray();
        } catch (Throwable $e) {
            throw UserCreationFailedException::fromException($e);
        }
    }

    public function update(string $id, array $data): bool
    {
        try {
            $user = User::findOrFail($id);
            return $user->update($data);
        } catch (Throwable $e) {
            throw UserUpdateFailedException::fromException($id, $e);
        }
    }

    public function delete(string $id): bool
    {
        try {
            $user = User::findOrFail($id);
            return $user->delete();
        } catch (Throwable $e) {
            return false;
        }
    }
}

