<?php

declare(strict_types=1);

namespace App\Modules\Biometric\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;
use Throwable;

class BiometricUpdateFailedException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    public static function fromException(string $biometricId, Throwable $exception): self
    {
        return new self(
            "Failed to update biometric with ID {$biometricId}",
            0,
            $exception,
            ['biometric_id' => $biometricId, 'error' => $exception->getMessage()]
        );
    }
}

