<?php

declare(strict_types=1);

namespace App\Modules\Ingredient\Application\Services;

use App\Modules\Ingredient\Application\DTOs\CreateIngredientDTO;
use App\Modules\Ingredient\Application\DTOs\IngredientFilterDTO;
use App\Modules\Ingredient\Application\DTOs\UpdateIngredientDTO;
use App\Modules\Ingredient\Domain\Contracts\IngredientRepositoryInterface;
use App\Modules\Ingredient\Domain\Exceptions\IngredientNotFoundException;
use Throwable;

class IngredientService
{
    public function __construct(
        private readonly IngredientRepositoryInterface $repository
    ) {
    }

    public function findAll(IngredientFilterDTO $filters): array
    {
        return $this->repository->findAll([
            'search' => $filters->search,
            'userId' => $filters->userId,
            'unit' => $filters->unit,
            'page' => $filters->page,
            'perPage' => $filters->perPage,
        ]);
    }

    public function findById(string $id): array
    {
        $ingredient = $this->repository->findById($id);

        if ($ingredient === null) {
            throw IngredientNotFoundException::withId($id);
        }

        return $ingredient;
    }

    public function create(CreateIngredientDTO $dto): array
    {
        return $this->repository->create([
            'name' => $dto->name,
            'amount' => $dto->amount,
            'unit' => $dto->unit,
            'kcal' => $dto->kcal,
            'prot' => $dto->prot,
            'carb' => $dto->carb,
            'fat' => $dto->fat,
            'user_id' => $dto->userId,
        ]);
    }

    public function update(UpdateIngredientDTO $dto): array
    {
        $ingredient = $this->repository->findById($dto->ingredientId);

        if ($ingredient === null) {
            throw IngredientNotFoundException::withId($dto->ingredientId);
        }

        $updateData = array_filter([
            'name' => $dto->name,
            'amount' => $dto->amount,
            'unit' => $dto->unit,
            'kcal' => $dto->kcal,
            'prot' => $dto->prot,
            'carb' => $dto->carb,
            'fat' => $dto->fat,
        ], fn ($value) => $value !== null);

        $this->repository->update($dto->ingredientId, $updateData);

        return $this->repository->findById($dto->ingredientId);
    }

    public function delete(string $id): bool
    {
        $ingredient = $this->repository->findById($id);

        if ($ingredient === null) {
            throw IngredientNotFoundException::withId($id);
        }

        return $this->repository->delete($id);
    }
}

