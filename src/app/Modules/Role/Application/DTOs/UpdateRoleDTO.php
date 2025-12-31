<?php

declare(strict_types=1);

namespace App\Modules\Role\Application\DTOs;

readonly class UpdateRoleDTO
{
    public function __construct(
        public string $roleId,
        public ?string $name,
    ) {
    }
}

