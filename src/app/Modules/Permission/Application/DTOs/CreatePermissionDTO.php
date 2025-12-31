<?php

declare(strict_types=1);

namespace App\Modules\Permission\Application\DTOs;

readonly class CreatePermissionDTO
{
    public function __construct(
        public string $name,
    ) {
    }
}

