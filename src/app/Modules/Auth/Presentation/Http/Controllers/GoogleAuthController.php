<?php

declare(strict_types=1);

namespace App\Modules\Auth\Presentation\Http\Controllers;

use App\Modules\Auth\Application\Services\GoogleAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

class GoogleAuthController extends Controller
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
}
