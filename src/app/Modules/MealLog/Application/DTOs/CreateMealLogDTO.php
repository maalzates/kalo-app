<?php

declare(strict_types=1);

namespace App\Modules\MealLog\Application\DTOs;

readonly class CreateMealLogDTO
{
    public function __construct(
        public string $userId,
        public ?string $ingredientId,
        public ?string $recipeId,
        public string $quantity,
        public string $unit,
        public ?string $loggedAt,
    ) {
    }
}

