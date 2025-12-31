<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;
    
    protected static ?string $password = null;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->optional(0.7)->phoneNumber(),
            'birth_date' => $this->faker->optional(0.8)->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'gender' => $this->faker->optional(0.9)->randomElement(['male', 'female', 'other']),
            'height' => $this->faker->optional(0.8)->numberBetween(150, 200),
            'google_id' => null,
            'auth_provider' => null,
            'password' => static::$password ??= Hash::make('password'),
            'role_id' => 1,
            'email_verified_at' => $this->faker->optional(0.9)->dateTimeBetween('-1 year', 'now'),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function withRole(int $roleId): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => $roleId,
        ]);
    }

    public function withGoogle(): static
    {
        return $this->state(fn (array $attributes) => [
            'google_id' => $this->faker->uuid(),
            'auth_provider' => 'google',
            'password' => null,
        ]);
    }

    public function male(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => $this->faker->name('male'),
                'gender' => 'male',
                'height' => $this->faker->numberBetween(165, 195),
            ];
        });
    }

    public function female(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => $this->faker->name('female'),
                'gender' => 'female',
                'height' => $this->faker->numberBetween(152, 180),
            ];
        });
    }
}
