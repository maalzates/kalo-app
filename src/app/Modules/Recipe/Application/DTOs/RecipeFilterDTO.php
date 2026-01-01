<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Application\DTOs;

readonly class RecipeFilterDTO
{
    public function __construct(
        public ?string $search,
        public ?string $userId,
        public ?int $page,
        public ?int $perPage,
        public ?bool $includePublic = false,
    ) {
    }
}

