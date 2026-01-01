<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Symfony\Component\HttpFoundation\Response;

class LogoutFailedException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    public static function forUser(int $userId): self
    {
        return new self(
            'Failed to logout user.',
            0,
            null,
            ['user_id' => $userId]
        );
    }
}

