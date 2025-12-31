<?php

declare(strict_types=1);

namespace App\Modules\Role\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;
use Throwable;

class RoleUpdateFailedException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    public static function fromException(string $id, Throwable $exception): self
    {
        return new self(
            "Failed to update role with ID {$id}",
            0,
            $exception,
            ['role_id' => $id, 'error' => $exception->getMessage()]
        );
    }
}

