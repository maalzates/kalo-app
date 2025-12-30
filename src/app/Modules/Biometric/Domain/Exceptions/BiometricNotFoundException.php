<?php

declare(strict_types=1);

namespace App\Modules\Biometric\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;

class BiometricNotFoundException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_NOT_FOUND;

    public static function withId(string $id): self
    {
        return new self(
            "Biometric with ID {$id} not found",
            0,
            null,
            ['biometric_id' => $id]
        );
    }
}

