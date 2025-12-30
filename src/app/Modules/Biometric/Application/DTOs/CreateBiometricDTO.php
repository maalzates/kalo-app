<?php

declare(strict_types=1);

namespace App\Modules\Biometric\Application\DTOs;

readonly class CreateBiometricDTO
{
    public function __construct(
        public string $userId,
        public string $weight,
        public ?string $fatPercentage,
        public ?string $cleanMass,
        public ?string $waistCircumference,
        public ?string $measuredAt,
    ) {
    }
}

