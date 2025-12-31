<?php

declare(strict_types=1);

namespace App\Modules\Macro\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;
use Throwable;

class MacroUpdateFailedException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    public static function fromException(string $id, Throwable $exception): self
    {
        return new self(
            "Failed to update macro with ID {$id}",
            0,
            $exception,
            ['macro_id' => $id, 'error' => $exception->getMessage()]
        );
    }
}

