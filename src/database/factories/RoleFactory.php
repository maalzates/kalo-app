<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Role\Infrastructure\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word() . '_role',
        ];
    }

    public function user(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'user',
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'admin',
        ]);
    }

    public function nutritionist(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'nutritionist',
        ]);
    }

    public function premiumUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'premium_user',
        ]);
    }
}

