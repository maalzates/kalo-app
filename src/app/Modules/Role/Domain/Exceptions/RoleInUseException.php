<?php

declare(strict_types=1);

namespace App\Modules\Role\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;

class RoleInUseException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_CONFLICT;

    public static function withId(string $id): self
    {
        return new self(
            "Cannot delete role with ID {$id} because it is assigned to users",
            0,
            null,
            ['role_id' => $id]
        );
    }
}

