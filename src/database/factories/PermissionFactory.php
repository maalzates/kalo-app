<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Permission\Infrastructure\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word() . '_' . $this->faker->word(),
        ];
    }

    public function crud(string $resource, string $action): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => "{$action}_{$resource}",
        ]);
    }
}

