<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\Services;

use App\Modules\Auth\Domain\Contracts\AuthRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthService
{
    public function __construct(
        private readonly AuthRepositoryInterface $authRepository
    ) {}

    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            if (!$googleUser->getEmail() || !$googleUser->getName()) {
                return $this->redirectToLoginWithError('Google authentication failed. Email or name not provided.');
            }

            $authData = $this->authRepository->loginOrRegisterWithGoogle($googleUser);

            // Redirect to frontend with token and user data
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
            $queryParams = http_build_query([
                'token' => $authData['access_token'],
                'user' => json_encode($authData['user']),
            ]);

            return redirect()->away("{$frontendUrl}/auth/callback?{$queryParams}");
        } catch (\Exception $e) {
            return $this->redirectToLoginWithError('Authentication failed. Please try again.');
        }
    }

    private function redirectToLoginWithError(string $error): RedirectResponse
    {
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
        return redirect()->away("{$frontendUrl}/login?error=" . urlencode($error));
    }
}
