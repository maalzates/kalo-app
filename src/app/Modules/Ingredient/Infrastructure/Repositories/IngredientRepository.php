<?php

declare(strict_types=1);

namespace App\Modules\Ingredient\Infrastructure\Repositories;

use App\Modules\Ingredient\Domain\Contracts\IngredientRepositoryInterface;
use App\Modules\Ingredient\Domain\Exceptions\IngredientCreationFailedException;
use App\Modules\Ingredient\Domain\Exceptions\IngredientUpdateFailedException;
use App\Models\Ingredient;
use Throwable;

class IngredientRepository implements IngredientRepositoryInterface
{
    public function findAll(array $filters): array
    {
        try {
            $query = Ingredient::query();

            if (isset($filters['search']) && $filters['search'] !== null) {
                $search = $filters['search'];
                $query->where('name', 'like', "%{$search}%");
            }

            // Filter by user_id: include public items if includePublic is true
            $includePublic = $filters['includePublic'] ?? false;
            if (isset($filters['userId']) && $filters['userId'] !== null) {
                if ($includePublic) {
                    // Include user's ingredients AND global ingredients (user_id null)
                    $query->where(function($q) use ($filters) {
                        $q->where('user_id', $filters['userId'])
                          ->orWhereNull('user_id');
                    });
                } else {
                    // Only user's private ingredients
                    $query->where('user_id', $filters['userId']);
                }
            } else {
                // If no userId and includePublic is true, show only global ingredients
                if ($includePublic) {
                    $query->whereNull('user_id');
                }
            }

            if (isset($filters['unit']) && $filters['unit'] !== null) {
                $query->where('unit', $filters['unit']);
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

    public function findById(string $id, int $userId): ?array
    {
        try {
            return ($ingredient = Ingredient::where('id', $id)
                ->where('user_id', $userId)
                ->first()) ? $ingredient->toArray() : null;
        } catch (Throwable $exception) {
            return null;
        }
    }

    public function create(array $data): array
    {
        try {
            return Ingredient::create($data)->toArray();
        } catch (Throwable $exception) {
            throw IngredientCreationFailedException::fromException($exception);
        }
    }

    public function update(string $id, array $data, int $userId): bool
    {
        try {
            return Ingredient::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail()
                ->update($data);
        } catch (Throwable $exception) {
            throw IngredientUpdateFailedException::fromException($id, $exception);
        }
    }

    public function delete(string $id, int $userId): bool
    {
        try {
            return Ingredient::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail()
                ->delete();
        } catch (Throwable $exception) {
            return false;
        }
    }
}

