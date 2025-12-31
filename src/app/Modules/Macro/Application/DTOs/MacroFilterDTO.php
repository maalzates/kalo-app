<?php

declare(strict_types=1);

namespace App\Modules\Macro\Application\DTOs;

readonly class MacroFilterDTO
{
    public function __construct(
        public ?string $userId,
        public ?int $page,
        public ?int $perPage,
    ) {
    }
}

