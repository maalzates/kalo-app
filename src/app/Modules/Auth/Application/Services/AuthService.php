<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\Services;

use App\Modules\Auth\Application\DTOs\RegisterUserDTO;
use App\Modules\Auth\Application\DTOs\LoginUserDTO;
use App\Modules\Auth\Domain\Contracts\AuthRepositoryInterface;

class AuthService
{
    public function __construct(
        private readonly AuthRepositoryInterface $authRepository
    ) {}

    public function register(RegisterUserDTO $dto): array
    {
        return $this->authRepository->register($dto);
    }

    public function login(LoginUserDTO $dto): array
    {
        return $this->authRepository->login($dto);
    }

    public function logout(int $userId): bool
    {
        return $this->authRepository->logout($userId);
    }
}

