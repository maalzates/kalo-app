<?php

declare(strict_types=1);

namespace App\Modules\Biometric\Application\DTOs;

readonly class BiometricFilterDTO
{
    public function __construct(
        public ?string $userId,
        public ?string $dateFrom,
        public ?string $dateTo,
        public ?int $page,
        public ?int $perPage,
    ) {
    }
}

