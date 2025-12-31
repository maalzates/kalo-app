<?php

declare(strict_types=1);

namespace App\Modules\Role\Application\DTOs;

readonly class CreateRoleDTO
{
    public function __construct(
        public string $name,
        public ?array $permissionIds,
    ) {
    }
}

