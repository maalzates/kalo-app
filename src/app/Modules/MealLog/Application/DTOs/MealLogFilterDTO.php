<?php

declare(strict_types=1);

namespace App\Modules\MealLog\Application\DTOs;

readonly class MealLogFilterDTO
{
    public function __construct(
        public ?string $userId,
        public ?string $dateFrom,
        public ?string $dateTo,
        public ?string $type,
        public ?int $page,
        public ?int $perPage,
    ) {
    }
}

