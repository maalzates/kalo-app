<?php

declare(strict_types=1);

namespace App\Modules\Permission\Application\DTOs;

readonly class PermissionFilterDTO
{
    public function __construct(
        public ?string $search,
        public ?int $page,
        public ?int $perPage,
    ) {
    }
}

