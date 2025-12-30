<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Infrastructure\Repositories;

use App\Modules\Recipe\Domain\Contracts\RecipeRepositoryInterface;
use App\Modules\Recipe\Domain\Exceptions\IngredientAttachmentFailedException;
use App\Modules\Recipe\Domain\Exceptions\RecipeCreationFailedException;
use App\Modules\Recipe\Domain\Exceptions\RecipeUpdateFailedException;
use App\Modules\Recipe\Infrastructure\Models\Recipe;
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

            if (isset($filters['userId']) && $filters['userId'] !== null) {
                $query->where('user_id', $filters['userId']);
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
            $recipe = Recipe::with('ingredients')->find($id);
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

    public function update(string $id, array $data): bool
    {
        try {
            $recipe = Recipe::findOrFail($id);
            return $recipe->update($data);
        } catch (Throwable $e) {
            throw RecipeUpdateFailedException::fromException($id, $e);
        }
    }

    public function delete(string $id): bool
    {
        try {
            $recipe = Recipe::findOrFail($id);
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

