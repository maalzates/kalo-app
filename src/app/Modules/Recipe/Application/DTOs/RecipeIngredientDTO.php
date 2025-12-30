<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Application\DTOs;

readonly class RecipeIngredientDTO
{
    public function __construct(
        public string $ingredientId,
        public string $amount,
        public string $unit,
    ) {
    }
}

