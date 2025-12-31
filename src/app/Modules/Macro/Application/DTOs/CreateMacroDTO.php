<?php

declare(strict_types=1);

namespace App\Modules\Macro\Application\DTOs;

readonly class CreateMacroDTO
{
    public function __construct(
        public int $kcal,
        public string $prot,
        public string $carb,
        public string $fat,
        public string $userId,
    ) {
    }
}

