<?php

declare(strict_types=1);

namespace App\Modules\Macro\Application\DTOs;

readonly class UpdateMacroDTO
{
    public function __construct(
        public string $macroId,
        public ?int $kcal,
        public ?string $prot,
        public ?string $carb,
        public ?string $fat,
    ) {
    }
}

