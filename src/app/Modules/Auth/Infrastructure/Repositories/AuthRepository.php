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
use Laravel\Socialite\Contracts\User as SocialiteUser;
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
        } catch (Throwable $exception) {
            throw RegistrationFailedException::forUserRegistration($dto);
        }
    }

    public function login(LoginUserDTO $dto): array
    {
        try {
            if (!Auth::attempt(['email' => $dto->email, 'password' => $dto->password])) {
                throw InvalidCredentialsException::forLogin($dto);
            }

            // Get user directly from database by email to avoid session conflicts
            // Don't use Auth::user() as it touches the session
            $user = User::with(['macros', 'biometrics'])
                ->where('email', $dto->email)
                ->firstOrFail();
            
            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user->toArray(),
                'access_token' => $token,
                'token_type' => 'Bearer',
            ];
        } catch (InvalidCredentialsException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            throw InvalidCredentialsException::forLogin($dto);
        }
    }

    public function logout(int $userId): bool
    {
        try {
            User::findOrFail($userId)->currentAccessToken()->delete();
            return true;
        } catch (Throwable $exception) {
            throw LogoutFailedException::forUser($userId);
        }
    }

    public function loginOrRegisterWithGoogle(SocialiteUser $googleUser): array
    {
        try {
            // Find user by email or create new one
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Update Google ID and auth provider if not set
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'auth_provider' => 'google',
                    ]);
                }
            } else {
                // Create new user with Google data
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'auth_provider' => 'google',
                    'email_verified_at' => now(),
                    'role_id' => 1, // Default user role
                ]);
            }

            // Reload user with relationships
            $user = User::with(['macros', 'biometrics'])->findOrFail($user->id);
            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user->toArray(),
                'access_token' => $token,
                'token_type' => 'Bearer',
            ];
        } catch (Throwable $exception) {
            throw new \Exception('Failed to authenticate with Google: ' . $exception->getMessage());
        }
    }
}

