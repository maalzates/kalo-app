<?php

declare(strict_types=1);

namespace App\Modules\Biometric\Application\DTOs;

readonly class UpdateBiometricDTO
{
    public function __construct(
        public string $biometricId,
        public ?string $weight,
        public ?string $fatPercentage,
        public ?string $cleanMass,
        public ?string $waistCircumference,
        public ?string $measuredAt,
    ) {
    }
}

