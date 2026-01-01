<?php

declare(strict_types=1);

namespace App\Modules\Ingredient\Application\DTOs;

readonly class IngredientFilterDTO
{
    public function __construct(
        public ?string $search,
        public ?string $userId,
        public ?string $unit,
        public ?int $page,
        public ?int $perPage,
        public ?bool $includePublic = false,
    ) {
    }
}

