<?php

declare(strict_types=1);

namespace App\Modules\MealLog\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Illuminate\Http\Response;

class InvalidMealLogException extends ApiException
{
    protected int $httpStatusCode = Response::HTTP_UNPROCESSABLE_ENTITY;

    public static function missingIngredientOrRecipe(): self
    {
        return new self(
            'Meal log must have either an ingredient_id or recipe_id, but not both',
            0,
            null,
            ['ingredient_id' => 'Either ingredient_id or recipe_id is required, but not both']
        );
    }

    public static function bothProvided(): self
    {
        return new self(
            'Meal log cannot have both ingredient_id and recipe_id',
            0,
            null,
            ['ingredient_id' => 'Cannot provide both ingredient_id and recipe_id']
        );
    }
}

