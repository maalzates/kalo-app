<?php

declare(strict_types=1);

namespace App\Modules\MealLog\Application\DTOs;

readonly class CreateMealLogFromAIDTO
{
    public function __construct(
        public string $userId,
        public string $quantity,
        public string $unit,
        public string $aiName,
        public array $aiData,
        public ?string $loggedAt,
    ) {
    }
}

