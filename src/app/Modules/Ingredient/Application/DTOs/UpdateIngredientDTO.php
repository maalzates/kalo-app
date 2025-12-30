<?php

declare(strict_types=1);

namespace App\Modules\Ingredient\Application\DTOs;

readonly class UpdateIngredientDTO
{
    public function __construct(
        public string $ingredientId,
        public ?string $name,
        public ?string $amount,
        public ?string $unit,
        public ?int $kcal,
        public ?string $prot,
        public ?string $carb,
        public ?string $fat,
    ) {
    }
}

