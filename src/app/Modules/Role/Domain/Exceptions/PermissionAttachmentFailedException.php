<?php

declare(strict_types=1);

namespace App\Modules\Role\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;
use Throwable;

class PermissionAttachmentFailedException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    public static function fromException(string $roleId, string $permissionId, Throwable $exception): self
    {
        return new self(
            "Failed to attach permission to role",
            0,
            $exception,
            ['role_id' => $roleId, 'permission_id' => $permissionId, 'error' => $exception->getMessage()]
        );
    }
}

