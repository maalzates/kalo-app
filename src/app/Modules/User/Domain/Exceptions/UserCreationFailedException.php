<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;
use Throwable;

class UserCreationFailedException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    public static function fromException(Throwable $exception): self
    {
        return new self(
            'Failed to create user',
            0,
            $exception,
            ['error' => $exception->getMessage()]
        );
    }
}

