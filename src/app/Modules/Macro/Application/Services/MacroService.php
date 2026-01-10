<?php

declare(strict_types=1);

namespace App\Modules\Macro\Application\Services;

use App\Modules\Macro\Application\DTOs\CreateMacroDTO;
use App\Modules\Macro\Application\DTOs\MacroFilterDTO;
use App\Modules\Macro\Application\DTOs\UpdateMacroDTO;
use App\Modules\Macro\Domain\Contracts\MacroRepositoryInterface;
use App\Modules\Macro\Domain\Exceptions\MacroNotFoundException;

class MacroService
{
    public function __construct(
        private readonly MacroRepositoryInterface $repository
    ) {
    }

    public function findAll(MacroFilterDTO $filters): array
    {
        return $this->repository->findAll([
            'user_id' => $filters->userId,
            'page' => $filters->page,
            'perPage' => $filters->perPage,
        ]);
    }

    public function findById(string $id, int $userId): array
    {
        $macro = $this->repository->findById($id, $userId);

        if ($macro === null) {
            throw MacroNotFoundException::withId($id);
        }

        return $macro;
    }

    public function create(CreateMacroDTO $dto): array
    {
        // Check if a macro already exists for today
        $today = now()->toDateString();
        $existingMacro = $this->repository->findByUserIdAndDate((int) $dto->userId, $today);

        if ($existingMacro !== null) {
            // Update existing macro for today
            $existingMacro->update([
                'kcal' => $dto->kcal,
                'prot' => $dto->prot,
                'carb' => $dto->carb,
                'fat' => $dto->fat,
            ]);

            return $existingMacro->load('user')->toArray();
        }

        // Create new macro if none exists for today
        return $this->repository->create([
            'kcal' => $dto->kcal,
            'prot' => $dto->prot,
            'carb' => $dto->carb,
            'fat' => $dto->fat,
            'user_id' => $dto->userId,
        ]);
    }

    public function update(UpdateMacroDTO $dto, int $userId): array
    {
        $macro = $this->repository->findById($dto->macroId, $userId);

        if ($macro === null) {
            throw MacroNotFoundException::withId($dto->macroId);
        }

        $updateData = array_filter([
            'kcal' => $dto->kcal,
            'prot' => $dto->prot,
            'carb' => $dto->carb,
            'fat' => $dto->fat,
        ], fn ($value) => $value !== null);

        if (count($updateData) > 0) {
            $this->repository->update($dto->macroId, $updateData, $userId);
        }

        return $this->repository->findById($dto->macroId, $userId);
    }

    public function delete(string $id, int $userId): bool
    {
        $macro = $this->repository->findById($id, $userId);

        if ($macro === null) {
            throw MacroNotFoundException::withId($id);
        }

        return $this->repository->delete($id, $userId);
    }
}

