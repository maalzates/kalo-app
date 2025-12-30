<?php

declare(strict_types=1);

namespace App\Modules\Ingredient\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;
use Throwable;

class IngredientUpdateFailedException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    public static function fromException(string $ingredientId, Throwable $exception): self
    {
        return new self(
            "Failed to update ingredient with ID {$ingredientId}",
            0,
            $exception,
            ['ingredient_id' => $ingredientId, 'error' => $exception->getMessage()]
        );
    }
}

