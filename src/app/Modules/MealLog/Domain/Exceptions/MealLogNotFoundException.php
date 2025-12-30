<?php

declare(strict_types=1);

namespace App\Modules\MealLog\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;

class MealLogNotFoundException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_NOT_FOUND;

    public static function withId(string $id): self
    {
        return new self(
            "Meal log with ID {$id} not found",
            0,
            null,
            ['meal_log_id' => $id]
        );
    }
}

