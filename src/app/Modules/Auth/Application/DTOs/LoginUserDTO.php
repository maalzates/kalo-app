<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\DTOs;

class LoginUserDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}
}

