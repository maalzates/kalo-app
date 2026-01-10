<?php

declare(strict_types=1);

namespace App\Modules\User\Application\DTOs;

readonly class UpdateUserDTO
{
    public function __construct(
        public string $userId,
        public ?string $name,
        public ?string $email,
        public ?string $phone,
        public ?string $birthDate,
        public ?string $gender,
        public ?int $height,
        public ?float $weight,
        public ?string $roleId,
        public ?string $countryCode,
        public ?int $activityLevel,
        public ?string $goalType,
        public ?string $macroCalculationMode,
        public ?string $currentPassword,
        public ?string $newPassword,
    ) {
    }
}

