<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Contracts;

interface UserRepositoryInterface
{
    public function findAll(array $filters): array;

    public function findById(string $id): ?array;

    public function findByIdWithRelations(string $id): ?array;

    public function findByEmail(string $email): ?array;

    public function findUserForPasswordValidation(string $id): ?array;

    public function validatePassword(string $id, string $password): bool;

    public function create(array $data): array;

    public function update(string $id, array $data): bool;

    public function delete(string $id): bool;
}

