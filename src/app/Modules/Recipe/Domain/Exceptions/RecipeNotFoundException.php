<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;

class RecipeNotFoundException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_NOT_FOUND;

    public static function withId(string $id): self
    {
        return new self(
            "Recipe with ID {$id} not found",
            0,
            null,
            ['recipe_id' => $id]
        );
    }
}

