<?php

declare(strict_types=1);

namespace App\Modules\Auth\Presentation\Http\Controllers;

use App\Modules\Auth\Application\Services\AuthService;
use App\Modules\Auth\Presentation\Http\Requests\RegisterRequest;
use App\Modules\Auth\Presentation\Http\Requests\LoginRequest;
use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends ApiController
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->success(
            $this->authService->register($request->toDTO()),
            'User registered successfully',
            Response::HTTP_CREATED
        );
    }

    public function login(LoginRequest $request): JsonResponse
    {
        return $this->success(
            $this->authService->login($request->toDTO()),
            'Login successful'
        );
    }

    public function logout(): JsonResponse
    {
        return $this->success(
            $this->authService->logout(auth()->id()),
            'Logout successful',
            Response::HTTP_NO_CONTENT
        );
    }
}

