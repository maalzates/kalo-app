<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use App\Modules\Auth\Application\DTOs\RegisterUserDTO;
use Symfony\Component\HttpFoundation\Response;

class RegistrationFailedException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    public static function forUserRegistration(RegisterUserDTO $dto): self
    {
        return new self(
            'Failed to register user.',
            0,
            null,
            [
                'email' => $dto->email,
                'name' => $dto->name,
            ]
        );
    }
}

