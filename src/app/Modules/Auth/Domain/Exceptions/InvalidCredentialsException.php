<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use App\Modules\Auth\Application\DTOs\LoginUserDTO;
use Symfony\Component\HttpFoundation\Response;

class InvalidCredentialsException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_UNAUTHORIZED;

    public static function forLogin(LoginUserDTO $dto): self
    {
        return new self(
            'The provided credentials are incorrect.',
            0,
            null,
            ['email' => $dto->email]
        );
    }
}

