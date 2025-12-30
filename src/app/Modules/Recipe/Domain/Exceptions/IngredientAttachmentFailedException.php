<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;
use Throwable;

class IngredientAttachmentFailedException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    public static function fromException(string $recipeId, string $ingredientId, Throwable $exception): self
    {
        return new self(
            "Failed to attach ingredient {$ingredientId} to recipe {$recipeId}",
            0,
            $exception,
            ['recipe_id' => $recipeId, 'ingredient_id' => $ingredientId, 'error' => $exception->getMessage()]
        );
    }
}

