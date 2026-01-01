<?php

declare(strict_types=1);

namespace App\Modules\Macro\Domain\Contracts;

interface MacroRepositoryInterface
{
    public function findAll(array $filters): array;

    public function findById(string $id, int $userId): ?array;

    public function findByUserId(string $userId): ?array;

    public function create(array $data): array;

    public function update(string $id, array $data, int $userId): bool;

    public function delete(string $id, int $userId): bool;
}

