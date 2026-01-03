<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Application\DTOs;

readonly class UpdateRecipeDTO
{
    public function __construct(
        public string $recipeId,
        public ?string $name,
        public ?int $servings,
        public ?int $totalKcal,
        public ?string $totalProt,
        public ?string $totalCarb,
        public ?string $totalFat,
        public ?array $ingredients,
    ) {
    }
}

