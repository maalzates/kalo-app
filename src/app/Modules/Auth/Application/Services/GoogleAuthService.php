<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\Services;

use App\Modules\Auth\Domain\Contracts\AuthRepositoryInterface;
use App\Modules\Auth\Domain\Contracts\GoogleClientInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthService
{
    public function __construct(
        private readonly AuthRepositoryInterface $authRepository,
        private readonly GoogleClientInterface $googleClient
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
            $frontendUrl = env('FRONTEND_URL', 'http://localhost');
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
        $frontendUrl = env('FRONTEND_URL', 'http://localhost');
        return redirect()->away("{$frontendUrl}/login?error=" . urlencode($error));
    }

    /**
     * Authenticate user with Google ID Token (for mobile apps)
     *
     * @param string $idToken The ID token from Google Sign-In SDK
     * @return array Authentication data with user and access_token
     * @throws \Exception If token verification fails
     */
    public function authenticateWithIdToken(string $idToken): array
    {
        // Verify ID Token with Google
        $payload = $this->googleClient->verifyIdToken($idToken);

        if (!$payload) {
            throw new \Exception('Invalid Google ID token');
        }

        // Extract user data from token payload
        $email = $payload['email'] ?? null;
        $name = $payload['name'] ?? null;
        $googleId = $payload['sub'] ?? null;

        if (!$email || !$name || !$googleId) {
            throw new \Exception('Missing required user data in token');
        }

        // Create a Socialite-like user object
        $googleUser = new \stdClass();
        $googleUser->email = $email;
        $googleUser->name = $name;
        $googleUser->id = $googleId;

        // Use same repository method as web flow
        return $this->authRepository->loginOrRegisterWithGoogle($googleUser);
    }
}
