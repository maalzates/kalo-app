<?php

declare(strict_types=1);

namespace App\Modules\Permission\Application\DTOs;

readonly class UpdatePermissionDTO
{
    public function __construct(
        public string $permissionId,
        public ?string $name,
    ) {
    }
}

