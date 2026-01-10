<?php

declare(strict_types=1);

namespace App\Modules\User\Application\DTOs;

readonly class UpdateWeightDTO
{
    public function __construct(
        public string $userId,
        public float $weight,
    ) {
    }
}
