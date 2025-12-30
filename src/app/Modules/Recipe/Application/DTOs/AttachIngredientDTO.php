<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Application\DTOs;

readonly class AttachIngredientDTO
{
    public function __construct(
        public string $recipeId,
        public string $ingredientId,
        public string $amount,
        public string $unit,
    ) {
    }
}

