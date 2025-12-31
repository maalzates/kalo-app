<?php

declare(strict_types=1);

namespace App\Modules\User\Application\DTOs;

readonly class UpdateUserDTO
{
    public function __construct(
        public string $userId,
        public ?string $name,
        public ?string $email,
        public ?string $phone,
        public ?string $birthDate,
        public ?string $gender,
        public ?int $height,
        public ?string $roleId,
    ) {
    }
}

