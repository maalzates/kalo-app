<?php

declare(strict_types=1);

namespace App\Modules\Biometric\Application\Services;

use App\Modules\Biometric\Application\DTOs\BiometricFilterDTO;
use App\Modules\Biometric\Application\DTOs\CreateBiometricDTO;
use App\Modules\Biometric\Application\DTOs\UpdateBiometricDTO;
use App\Modules\Biometric\Domain\Contracts\BiometricRepositoryInterface;
use App\Modules\Biometric\Domain\Exceptions\BiometricNotFoundException;
use Throwable;

class BiometricService
{
    public function __construct(
        private readonly BiometricRepositoryInterface $repository
    ) {
    }

    public function findAll(BiometricFilterDTO $filters): array
    {
        return $this->repository->findAll([
            'userId' => $filters->userId,
            'dateFrom' => $filters->dateFrom,
            'dateTo' => $filters->dateTo,
            'page' => $filters->page,
            'perPage' => $filters->perPage,
        ]);
    }

    public function findById(string $id, int $userId): array
    {
        $biometric = $this->repository->findById($id, $userId);

        if ($biometric === null) {
            throw BiometricNotFoundException::withId($id);
        }

        return $biometric;
    }

    public function create(CreateBiometricDTO $dto): array
    {
        return $this->repository->create([
            'user_id' => $dto->userId,
            'weight' => $dto->weight,
            'fat_percentage' => $dto->fatPercentage,
            'clean_mass' => $dto->cleanMass,
            'waist_circumference' => $dto->waistCircumference,
            'measured_at' => $dto->measuredAt ?? now(),
        ]);
    }

    public function update(UpdateBiometricDTO $dto, int $userId): array
    {
        $biometric = $this->repository->findById($dto->biometricId, $userId);

        if ($biometric === null) {
            throw BiometricNotFoundException::withId($dto->biometricId);
        }

        $updateData = array_filter([
            'weight' => $dto->weight,
            'fat_percentage' => $dto->fatPercentage,
            'clean_mass' => $dto->cleanMass,
            'waist_circumference' => $dto->waistCircumference,
            'measured_at' => $dto->measuredAt,
        ], fn ($value) => $value !== null);

        $this->repository->update($dto->biometricId, $updateData, $userId);

        return $this->repository->findById($dto->biometricId, $userId);
    }

    public function delete(string $id, int $userId): bool
    {
        $biometric = $this->repository->findById($id, $userId);

        if ($biometric === null) {
            throw BiometricNotFoundException::withId($id);
        }

        return $this->repository->delete($id, $userId);
    }
}

