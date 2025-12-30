<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Domain\Contracts;

interface RecipeRepositoryInterface
{
    public function findAll(array $filters): array;

    public function findById(string $id): ?array;

    public function create(array $data): array;

    public function update(string $id, array $data): bool;

    public function delete(string $id): bool;

    public function attachIngredient(string $recipeId, string $ingredientId, array $pivotData): bool;

    public function detachIngredient(string $recipeId, string $ingredientId): bool;

    public function updatePivot(string $recipeId, string $ingredientId, array $pivotData): bool;
}

