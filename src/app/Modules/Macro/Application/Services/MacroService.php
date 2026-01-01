<?php

declare(strict_types=1);

namespace App\Modules\Macro\Application\Services;

use App\Modules\Macro\Application\DTOs\CreateMacroDTO;
use App\Modules\Macro\Application\DTOs\MacroFilterDTO;
use App\Modules\Macro\Application\DTOs\UpdateMacroDTO;
use App\Modules\Macro\Domain\Contracts\MacroRepositoryInterface;
use App\Modules\Macro\Domain\Exceptions\DuplicateMacroException;
use App\Modules\Macro\Domain\Exceptions\MacroNotFoundException;
use Throwable;

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
        // Permitir múltiples macros por usuario para mantener historial
        // Ya no validamos si existe un macro previo, permitimos crear nuevos
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

