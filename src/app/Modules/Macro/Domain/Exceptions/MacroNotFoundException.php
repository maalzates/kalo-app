<?php

declare(strict_types=1);

namespace App\Modules\Macro\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;

class MacroNotFoundException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_NOT_FOUND;

    public static function withId(string $id): self
    {
        return new self(
            "Macro with ID {$id} not found",
            0,
            null,
            ['macro_id' => $id]
        );
    }

    public static function withUserId(string $userId): self
    {
        return new self(
            "Macro for user with ID {$userId} not found",
            0,
            null,
            ['user_id' => $userId]
        );
    }
}

