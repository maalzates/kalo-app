<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\DTOs;

class RegisterUserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $phone,
        public readonly ?string $birthDate,
        public readonly ?string $gender,
        public readonly ?int $height,
    ) {}
}

