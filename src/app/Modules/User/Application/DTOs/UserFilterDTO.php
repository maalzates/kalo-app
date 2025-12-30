<?php

declare(strict_types=1);

namespace App\Modules\User\Application\DTOs;

readonly class UserFilterDTO
{
    public function __construct(
        public ?string $search,
        public ?string $gender,
        public ?int $page,
        public ?int $perPage,
    ) {
    }
}

