<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infrastructure\Repositories;

use App\Modules\Auth\Application\DTOs\RegisterUserDTO;
use App\Modules\Auth\Application\DTOs\LoginUserDTO;
use App\Modules\Auth\Domain\Contracts\AuthRepositoryInterface;
use App\Modules\Auth\Domain\Exceptions\InvalidCredentialsException;
use App\Modules\Auth\Domain\Exceptions\RegistrationFailedException;
use App\Modules\Auth\Domain\Exceptions\LogoutFailedException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthRepository implements AuthRepositoryInterface
{
    public function register(RegisterUserDTO $dto): array
    {
        try {
            $user = User::create([
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => Hash::make($dto->password),
                'phone' => $dto->phone,
                'birth_date' => $dto->birthDate,
                'gender' => $dto->gender,
                'height' => $dto->height,
                'role_id' => 1, // Default user role
                'email_verified_at' => now(),
            ]);

            // Reload user with relationships from database to avoid session conflicts
            $user = User::with(['macros', 'biometrics'])->findOrFail($user->id);
            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user->toArray(),
                'access_token' => $token,
                'token_type' => 'Bearer',
            ];
        } catch (Throwable) {
            throw RegistrationFailedException::forUserRegistration($dto);
        }
    }

    public function login(LoginUserDTO $dto): array
    {
        try {
            if (!Auth::attempt(['email' => $dto->email, 'password' => $dto->password])) {
                throw InvalidCredentialsException::forLogin($dto);
            }

            $authenticatedUser = Auth::user();
            $user = User::with(['macros', 'biometrics'])->findOrFail($authenticatedUser->id);
            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user->toArray(),
                'access_token' => $token,
                'token_type' => 'Bearer',
            ];
        } catch (InvalidCredentialsException $exception) {
            throw $exception;
        } catch (Throwable) {
            throw InvalidCredentialsException::forLogin($dto);
        }
    }

    public function logout(int $userId): bool
    {
        try {
            $user = User::findOrFail($userId);
            $user->currentAccessToken()->delete();

            return true;
        } catch (Throwable) {
            throw LogoutFailedException::forUser($userId);
        }
    }
}

