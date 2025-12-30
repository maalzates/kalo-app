<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;

class UserNotFoundException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_NOT_FOUND;

    public static function withId(string $id): self
    {
        return new self(
            "User with ID {$id} not found",
            0,
            null,
            ['user_id' => $id]
        );
    }

    public static function withEmail(string $email): self
    {
        return new self(
            "User with email {$email} not found",
            0,
            null,
            ['email' => $email]
        );
    }
}

