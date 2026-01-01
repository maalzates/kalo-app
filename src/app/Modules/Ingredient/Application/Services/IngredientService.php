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
            'includePublic' => $filters->includePublic,
        ]);
    }

    public function findById(string $id, int $userId): array
    {
        $ingredient = $this->repository->findById($id, $userId);

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

    public function update(UpdateIngredientDTO $dto, int $userId): array
    {
        $ingredient = $this->repository->findById($dto->ingredientId, $userId);

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

        $this->repository->update($dto->ingredientId, $updateData, $userId);

        return $this->repository->findById($dto->ingredientId, $userId);
    }

    public function delete(string $id, int $userId): bool
    {
        $ingredient = $this->repository->findById($id, $userId);

        if ($ingredient === null) {
            throw IngredientNotFoundException::withId($id);
        }

        return $this->repository->delete($id, $userId);
    }
}

