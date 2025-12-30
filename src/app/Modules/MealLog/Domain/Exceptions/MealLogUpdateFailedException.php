<?php

declare(strict_types=1);

namespace App\Modules\MealLog\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;
use Throwable;

class MealLogUpdateFailedException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    public static function fromException(string $mealLogId, Throwable $exception): self
    {
        return new self(
            "Failed to update meal log with ID {$mealLogId}",
            0,
            $exception,
            ['meal_log_id' => $mealLogId, 'error' => $exception->getMessage()]
        );
    }
}

