<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;
use Throwable;

class RecipeUpdateFailedException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    public static function fromException(string $recipeId, Throwable $exception): self
    {
        return new self(
            "Failed to update recipe with ID {$recipeId}",
            0,
            $exception,
            ['recipe_id' => $recipeId, 'error' => $exception->getMessage()]
        );
    }
}

