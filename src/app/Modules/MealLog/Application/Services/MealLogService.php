<?php

declare(strict_types=1);

namespace App\Modules\MealLog\Application\Services;

use App\Modules\MealLog\Application\DTOs\CreateMealLogDTO;
use App\Modules\MealLog\Application\DTOs\MealLogFilterDTO;
use App\Modules\MealLog\Application\DTOs\UpdateMealLogDTO;
use App\Modules\MealLog\Domain\Contracts\MealLogRepositoryInterface;
use App\Modules\MealLog\Domain\Exceptions\InvalidMealLogException;
use App\Modules\MealLog\Domain\Exceptions\MealLogNotFoundException;
use Throwable;

class MealLogService
{
    public function __construct(
        private readonly MealLogRepositoryInterface $repository
    ) {
    }

    public function findAll(MealLogFilterDTO $filters): array
    {
        return $this->repository->findAll([
            'userId' => $filters->userId,
            'dateFrom' => $filters->dateFrom,
            'dateTo' => $filters->dateTo,
            'type' => $filters->type,
            'page' => $filters->page,
            'perPage' => $filters->perPage,
        ]);
    }

    public function findById(string $id, int $userId): array
    {
        $mealLog = $this->repository->findById($id, $userId);

        if ($mealLog === null) {
            throw MealLogNotFoundException::withId($id);
        }

        return $mealLog;
    }

    public function create(CreateMealLogDTO $dto): array
    {
        if ($dto->ingredientId === null && $dto->recipeId === null) {
            throw InvalidMealLogException::missingIngredientOrRecipe();
        }

        if ($dto->ingredientId !== null && $dto->recipeId !== null) {
            throw InvalidMealLogException::bothProvided();
        }

        return $this->repository->create([
            'user_id' => $dto->userId,
            'ingredient_id' => $dto->ingredientId,
            'recipe_id' => $dto->recipeId,
            'quantity' => $dto->quantity,
            'unit' => $dto->unit,
            'logged_at' => $dto->loggedAt ?? now(),
        ]);
    }

    public function update(UpdateMealLogDTO $dto, int $userId): array
    {
        $mealLog = $this->repository->findById($dto->mealLogId, $userId);

        if ($mealLog === null) {
            throw MealLogNotFoundException::withId($dto->mealLogId);
        }

        if ($dto->ingredientId !== null && $dto->recipeId !== null) {
            throw InvalidMealLogException::bothProvided();
        }

        $currentIngredientId = $mealLog['ingredient_id'] ?? null;
        $currentRecipeId = $mealLog['recipe_id'] ?? null;

        $newIngredientId = $dto->ingredientId ?? $currentIngredientId;
        $newRecipeId = $dto->recipeId ?? $currentRecipeId;

        if ($newIngredientId === null && $newRecipeId === null) {
            throw InvalidMealLogException::missingIngredientOrRecipe();
        }

        $updateData = array_filter([
            'ingredient_id' => $dto->ingredientId,
            'recipe_id' => $dto->recipeId,
            'quantity' => $dto->quantity,
            'unit' => $dto->unit,
            'logged_at' => $dto->loggedAt,
        ], fn ($value) => $value !== null);

        $this->repository->update($dto->mealLogId, $updateData, $userId);

        return $this->repository->findById($dto->mealLogId, $userId);
    }

    public function delete(string $id, int $userId): bool
    {
        $mealLog = $this->repository->findById($id, $userId);

        if ($mealLog === null) {
            throw MealLogNotFoundException::withId($id);
        }

        return $this->repository->delete($id, $userId);
    }
}

