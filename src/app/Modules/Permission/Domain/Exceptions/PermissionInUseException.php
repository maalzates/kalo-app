<?php

declare(strict_types=1);

namespace App\Modules\Permission\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;

class PermissionInUseException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_CONFLICT;

    public static function withId(string $id): self
    {
        return new self(
            "Cannot delete permission with ID {$id} because it is assigned to roles",
            0,
            null,
            ['permission_id' => $id]
        );
    }
}

