<?php

declare(strict_types=1);

namespace App\Modules\Permission\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;

class PermissionNotFoundException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_NOT_FOUND;

    public static function withId(string $id): self
    {
        return new self(
            "Permission with ID {$id} not found",
            0,
            null,
            ['permission_id' => $id]
        );
    }
}

