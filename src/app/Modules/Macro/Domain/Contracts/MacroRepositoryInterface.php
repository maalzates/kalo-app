<?php

declare(strict_types=1);

namespace App\Modules\Macro\Domain\Contracts;

interface MacroRepositoryInterface
{
    public function findAll(array $filters): array;

    public function findById(string $id): ?array;

    public function findByUserId(string $userId): ?array;

    public function create(array $data): array;

    public function update(string $id, array $data): bool;

    public function delete(string $id): bool;
}

