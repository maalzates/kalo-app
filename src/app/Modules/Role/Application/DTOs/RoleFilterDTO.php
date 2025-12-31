<?php

declare(strict_types=1);

namespace App\Modules\Role\Application\DTOs;

readonly class RoleFilterDTO
{
    public function __construct(
        public ?string $search,
        public ?int $page,
        public ?int $perPage,
    ) {
    }
}

