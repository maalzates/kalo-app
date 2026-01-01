<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Contracts;

use App\Modules\Auth\Application\DTOs\RegisterUserDTO;
use App\Modules\Auth\Application\DTOs\LoginUserDTO;

interface AuthRepositoryInterface
{
    public function register(RegisterUserDTO $dto): array;
    
    public function login(LoginUserDTO $dto): array;
    
    public function logout(int $userId): bool;
}

