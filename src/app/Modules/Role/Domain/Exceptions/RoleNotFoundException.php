<?php

declare(strict_types=1);

namespace App\Modules\Role\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;

class RoleNotFoundException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_NOT_FOUND;

    public static function withId(string $id): self
    {
        return new self(
            "Role with ID {$id} not found",
            0,
            null,
            ['role_id' => $id]
        );
    }
}

