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

            // Include user's recipes AND global recipes (user_id null)
            if (isset($filters['userId']) && $filters['userId'] !== null) {
                $query->where(function($q) use ($filters) {
                    $q->where('user_id', $filters['userId'])
                      ->orWhereNull('user_id');
                });
            } else {
                // If no userId, only show global recipes
                $query->whereNull('user_id');
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

    public function findById(string $id, int $userId): ?array
    {
        try {
            // Allow access to user's recipes OR global recipes
            $recipe = Recipe::with('ingredients')
                ->where('id', $id)
                ->where(function($q) use ($userId) {
                    $q->where('user_id', $userId)
                      ->orWhereNull('user_id');
                })
                ->first();
            return $recipe ? $recipe->toArray() : null;
        } catch (Throwable $e) {
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
        } catch (Throwable $e) {
            throw RecipeCreationFailedException::fromException($e);
        }
    }

    public function update(string $id, array $data, int $userId): bool
    {
        try {
            $recipe = Recipe::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();
            return $recipe->update($data);
        } catch (Throwable $e) {
            throw RecipeUpdateFailedException::fromException($id, $e);
        }
    }

    public function delete(string $id, int $userId): bool
    {
        try {
            $recipe = Recipe::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();
            return $recipe->delete();
        } catch (Throwable $e) {
            return false;
        }
    }

    public function attachIngredient(string $recipeId, string $ingredientId, array $pivotData): bool
    {
        try {
            $recipe = Recipe::findOrFail($recipeId);
            $recipe->ingredients()->attach($ingredientId, $pivotData);
            return true;
        } catch (Throwable $e) {
            throw IngredientAttachmentFailedException::fromException($recipeId, $ingredientId, $e);
        }
    }

    public function detachIngredient(string $recipeId, string $ingredientId): bool
    {
        try {
            $recipe = Recipe::findOrFail($recipeId);
            $recipe->ingredients()->detach($ingredientId);
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    public function updatePivot(string $recipeId, string $ingredientId, array $pivotData): bool
    {
        try {
            $recipe = Recipe::findOrFail($recipeId);
            $recipe->ingredients()->updateExistingPivot($ingredientId, $pivotData);
            return true;
        } catch (Throwable $e) {
            throw IngredientAttachmentFailedException::fromException($recipeId, $ingredientId, $e);
        }
    }
}

