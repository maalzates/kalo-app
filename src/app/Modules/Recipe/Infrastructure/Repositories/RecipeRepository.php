<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Infrastructure\Repositories;

use App\Modules\Recipe\Domain\Contracts\RecipeRepositoryInterface;
use App\Modules\Recipe\Domain\Exceptions\IngredientAttachmentFailedException;
use App\Modules\Recipe\Domain\Exceptions\RecipeCreationFailedException;
use App\Modules\Recipe\Domain\Exceptions\RecipeUpdateFailedException;
use App\Models\Recipe;
use Throwable;

class RecipeRepository implements RecipeRepositoryInterface
{
    public function findAll(array $filters): array
    {
        try {
            $query = Recipe::query();

            if (isset($filters['search']) && $filters['search'] !== null) {
                $search = $filters['search'];
                $query->where('name', 'like', "%{$search}%");
            }

            // Filter by user_id: include public items if includePublic is true
            $includePublic = $filters['includePublic'] ?? false;
            if (isset($filters['userId']) && $filters['userId'] !== null) {
                if ($includePublic) {
                    // Include user's recipes AND global recipes (user_id null)
                    $query->where(function($q) use ($filters) {
                        $q->where('user_id', $filters['userId'])
                          ->orWhereNull('user_id');
                    });
                } else {
                    // Only user's private recipes
                    $query->where('user_id', $filters['userId']);
                }
            } else {
                // If no userId and includePublic is true, show only global recipes
                if ($includePublic) {
                    $query->whereNull('user_id');
                }
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
            return ($recipe = Recipe::with('ingredients')
                ->where('id', $id)
                ->where('user_id', $userId)
                ->first()) ? $recipe->toArray() : null;
        } catch (Throwable $exception) {
            return null;
        }
    }

    public function create(array $data): array
    {
        try {
            $ingredients = $data['ingredients'] ?? [];
            unset($data['ingredients']);

            $recipe = Recipe::create($data);

            if (! empty($ingredients)) {
                $pivotData = [];
                foreach ($ingredients as $ingredient) {
                    $pivotData[$ingredient['ingredient_id']] = [
                        'amount' => $ingredient['amount'],
                        'unit' => $ingredient['unit'],
                    ];
                }
                $recipe->ingredients()->attach($pivotData);
            }

            return $recipe->load('ingredients')->toArray();
        } catch (Throwable $exception) {
            throw RecipeCreationFailedException::fromException($exception);
        }
    }

    public function update(string $id, array $data, ?array $ingredients, int $userId): bool
    {
        try {
            $recipe = Recipe::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();

            $recipe->update($data);

            // Si se proporcionan ingredientes (no null y no vacío), sincronizar la relación
            // Esto reemplaza todos los ingredientes existentes con los nuevos
            if ($ingredients !== null && !empty($ingredients)) {
                $pivotData = [];
                foreach ($ingredients as $ingredient) {
                    $pivotData[$ingredient['ingredient_id']] = [
                        'amount' => $ingredient['amount'],
                        'unit' => $ingredient['unit'],
                    ];
                }
                $recipe->ingredients()->sync($pivotData);
            }
            // Si ingredients es null, no hacer nada (mantener los ingredientes existentes)
            // Si ingredients es array vacío [], eliminar todos los ingredientes

            return true;
        } catch (Throwable $exception) {
            throw RecipeUpdateFailedException::fromException($id, $exception);
        }
    }

    public function delete(string $id, int $userId): bool
    {
        try {
            return Recipe::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail()
                ->delete();
        } catch (Throwable $exception) {
            return false;
        }
    }

    public function attachIngredient(string $recipeId, string $ingredientId, array $pivotData): bool
    {
        try {
            Recipe::findOrFail($recipeId)->ingredients()->attach($ingredientId, $pivotData);
            return true;
        } catch (Throwable $exception) {
            throw IngredientAttachmentFailedException::fromException($recipeId, $ingredientId, $exception);
        }
    }

    public function detachIngredient(string $recipeId, string $ingredientId): bool
    {
        try {
            Recipe::findOrFail($recipeId)->ingredients()->detach($ingredientId);
            return true;
        } catch (Throwable $exception) {
            return false;
        }
    }

    public function updatePivot(string $recipeId, string $ingredientId, array $pivotData): bool
    {
        try {
            Recipe::findOrFail($recipeId)->ingredients()->updateExistingPivot($ingredientId, $pivotData);
            return true;
        } catch (Throwable $exception) {
            throw IngredientAttachmentFailedException::fromException($recipeId, $ingredientId, $exception);
        }
    }
}

