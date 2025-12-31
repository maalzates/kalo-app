<?php

declare(strict_types=1);

namespace App\Modules\Macro\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;

class DuplicateMacroException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_CONFLICT;

    public static function withUserId(string $userId): self
    {
        return new self(
            "User with ID {$userId} already has macro goals defined",
            0,
            null,
            ['user_id' => $userId]
        );
    }
}

