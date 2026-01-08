<?php

declare(strict_types=1);

namespace App\Modules\Auth\Presentation\Http\Controllers;

use App\Modules\Auth\Application\Services\GoogleAuthService;
use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GoogleAuthController extends ApiController
{
    public function __construct(
        private readonly GoogleAuthService $googleAuthService
    ) {}

    public function redirect(): RedirectResponse
    {
        return $this->googleAuthService->redirectToGoogle();
    }

    public function callback(): RedirectResponse
    {
        return $this->googleAuthService->handleGoogleCallback();
    }

    /**
     * Authenticate with Google ID Token (Mobile Apps)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function authenticateWithIdToken(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_token' => ['required', 'string'],
        ]);

        try {
            $authData = $this->googleAuthService->authenticateWithIdToken($validated['id_token']);

            return $this->success(
                $authData,
                'Google authentication successful'
            );
        } catch (\Exception $e) {
            return $this->error(
                $e->getMessage(),
                Response::HTTP_UNAUTHORIZED
            );
        }
    }
}
