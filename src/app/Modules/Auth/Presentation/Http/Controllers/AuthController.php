<?php

declare(strict_types=1);

namespace App\Modules\Auth\Presentation\Http\Controllers;

use App\Modules\Auth\Application\Services\AuthService;
use App\Modules\Auth\Domain\Exceptions\InvalidCredentialsException;
use App\Modules\Auth\Domain\Exceptions\RegistrationFailedException;
use App\Modules\Auth\Domain\Exceptions\LogoutFailedException;
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
        try {
            $result = $this->authService->register($request->toDTO());

            return $this->success(
                $result,
                'User registered successfully',
                Response::HTTP_CREATED
            );
        } catch (RegistrationFailedException $e) {
            return $this->error(
                $e->getMessage(),
                $e->getHttpStatusCode(),
                $e->getContext()
            );
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login($request->toDTO());

            return $this->success(
                $result,
                'Login successful'
            );
        } catch (InvalidCredentialsException $e) {
            return $this->error(
                $e->getMessage(),
                $e->getHttpStatusCode(),
                $e->getContext()
            );
        }
    }

    public function logout(): JsonResponse
    {
        try {
            $this->authService->logout(auth()->id());

            return $this->success(
                null,
                'Logout successful',
                Response::HTTP_NO_CONTENT
            );
        } catch (LogoutFailedException $e) {
            return $this->error(
                $e->getMessage(),
                $e->getHttpStatusCode(),
                $e->getContext()
            );
        }
    }
}

