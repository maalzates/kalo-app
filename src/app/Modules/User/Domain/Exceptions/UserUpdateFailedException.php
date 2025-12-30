<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;
use Throwable;

class UserUpdateFailedException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    public static function fromException(string $userId, Throwable $exception): self
    {
        return new self(
            "Failed to update user with ID {$userId}",
            0,
            $exception,
            ['user_id' => $userId, 'error' => $exception->getMessage()]
        );
    }
}

