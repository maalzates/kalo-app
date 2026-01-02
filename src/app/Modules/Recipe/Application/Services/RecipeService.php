<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Application\Services;

use App\Modules\Recipe\Application\DTOs\AttachIngredientDTO;
use App\Modules\Recipe\Application\DTOs\CreateRecipeDTO;
use App\Modules\Recipe\Application\DTOs\RecipeFilterDTO;
use App\Modules\Recipe\Application\DTOs\UpdateRecipeDTO;
use App\Modules\Recipe\Domain\Contracts\RecipeRepositoryInterface;
use App\Modules\Recipe\Domain\Exceptions\RecipeNotFoundException;

class RecipeService
{
    public function __construct(
        private readonly RecipeRepositoryInterface $repository
    ) {
    }

    public function findAll(RecipeFilterDTO $filters): array
    {
        return $this->repository->findAll([
            'search' => $filters->search,
            'userId' => $filters->userId,
            'page' => $filters->page,
            'perPage' => $filters->perPage,
            'includePublic' => $filters->includePublic,
        ]);
    }

    public function findById(string $id, int $userId): array
    {
        $recipe = $this->repository->findById($id, $userId);

        if ($recipe === null) {
            throw RecipeNotFoundException::withId($id);
        }

        return $recipe;
    }

    public function create(CreateRecipeDTO $dto): array
    {
        $ingredients = [];
        if ($dto->ingredients !== null) {
            foreach ($dto->ingredients as $ingredient) {
                $ingredients[] = [
                    'ingredient_id' => $ingredient->ingredientId,
                    'amount' => $ingredient->amount,
                    'unit' => $ingredient->unit,
                ];
            }
        }

        return $this->repository->create([
            'name' => $dto->name,
            'servings' => $dto->servings,
            'total_kcal' => $dto->totalKcal,
            'total_prot' => $dto->totalProt,
            'total_carb' => $dto->totalCarb,
            'total_fat' => $dto->totalFat,
            'user_id' => $dto->userId,
            'ingredients' => $ingredients,
        ]);
    }

    public function update(UpdateRecipeDTO $dto, int $userId): array
    {
        $recipe = $this->repository->findById($dto->recipeId, $userId);

        if ($recipe === null) {
            throw RecipeNotFoundException::withId($dto->recipeId);
        }

        $updateData = array_filter([
            'name' => $dto->name,
            'servings' => $dto->servings,
            'total_kcal' => $dto->totalKcal,
            'total_prot' => $dto->totalProt,
            'total_carb' => $dto->totalCarb,
            'total_fat' => $dto->totalFat,
        ], fn ($value) => $value !== null);

        $this->repository->update($dto->recipeId, $updateData, $userId);

        return $this->repository->findById($dto->recipeId, $userId);
    }

    public function delete(string $id, int $userId): bool
    {
        $recipe = $this->repository->findById($id, $userId);

        if ($recipe === null) {
            throw RecipeNotFoundException::withId($id);
        }

        return $this->repository->delete($id, $userId);
    }

    public function attachIngredient(AttachIngredientDTO $dto, int $userId): bool
    {
        $recipe = $this->repository->findById($dto->recipeId, $userId);

        if ($recipe === null) {
            throw RecipeNotFoundException::withId($dto->recipeId);
        }

        return $this->repository->attachIngredient(
            $dto->recipeId,
            $dto->ingredientId,
            [
                'amount' => $dto->amount,
                'unit' => $dto->unit,
            ]
        );
    }

    public function detachIngredient(string $recipeId, string $ingredientId, int $userId): bool
    {
        $recipe = $this->repository->findById($recipeId, $userId);

        if ($recipe === null) {
            throw RecipeNotFoundException::withId($recipeId);
        }

        return $this->repository->detachIngredient($recipeId, $ingredientId);
    }

    public function updatePivot(AttachIngredientDTO $dto, int $userId): bool
    {
        $recipe = $this->repository->findById($dto->recipeId, $userId);

        if ($recipe === null) {
            throw RecipeNotFoundException::withId($dto->recipeId);
        }

        return $this->repository->updatePivot(
            $dto->recipeId,
            $dto->ingredientId,
            [
                'amount' => $dto->amount,
                'unit' => $dto->unit,
            ]
        );
    }
}

