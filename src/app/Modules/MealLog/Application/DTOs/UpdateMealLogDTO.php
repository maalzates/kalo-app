<?php

declare(strict_types=1);

namespace App\Modules\MealLog\Application\DTOs;

readonly class UpdateMealLogDTO
{
    public function __construct(
        public string $mealLogId,
        public ?string $ingredientId,
        public ?string $recipeId,
        public ?string $quantity,
        public ?string $unit,
        public ?string $loggedAt,
    ) {
    }
}

