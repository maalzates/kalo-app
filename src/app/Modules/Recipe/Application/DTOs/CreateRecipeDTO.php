<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Application\DTOs;

readonly class CreateRecipeDTO
{
    public function __construct(
        public string $name,
        public int $servings,
        public int $totalKcal,
        public string $totalProt,
        public string $totalCarb,
        public string $totalFat,
        public string $userId,
        public ?array $ingredients,
    ) {
    }
}

