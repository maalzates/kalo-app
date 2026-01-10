<?php

declare(strict_types=1);

namespace App\Modules\Macro\Domain\Contracts;

use App\Models\Macro;

interface MacroRepositoryInterface
{
    public function findAll(array $filters): array;

    public function findById(string $id, int $userId): ?array;

    public function findByUserId(string $userId): ?array;

    public function findByUserIdAndDate(int $userId, string $date): ?Macro;

    public function create(array $data): array;

    public function update(string $id, array $data, int $userId): bool;

    public function delete(string $id, int $userId): bool;
}

