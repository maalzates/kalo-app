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

            if (isset($filters['userId']) && $filters['userId'] !== null) {
                $query->where('user_id', $filters['userId']);
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
            $ingredient = Ingredient::find($id);
            return $ingredient ? $ingredient->toArray() : null;
        } catch (Throwable $e) {
            return null;
        }
    }

    public function create(array $data): array
    {
        try {
            $ingredient = Ingredient::create($data);
            return $ingredient->toArray();
        } catch (Throwable $e) {
            throw IngredientCreationFailedException::fromException($e);
        }
    }

    public function update(string $id, array $data): bool
    {
        try {
            $ingredient = Ingredient::findOrFail($id);
            return $ingredient->update($data);
        } catch (Throwable $e) {
            throw IngredientUpdateFailedException::fromException($id, $e);
        }
    }

    public function delete(string $id): bool
    {
        try {
            $ingredient = Ingredient::findOrFail($id);
            return $ingredient->delete();
        } catch (Throwable $e) {
            return false;
        }
    }
}

