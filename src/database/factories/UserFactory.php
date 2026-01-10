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
        $birthDate = $this->faker->optional(0.8)->dateTimeBetween('-60 years', '-18 years');
        $emailVerifiedAt = $this->faker->optional(0.9)->dateTimeBetween('-1 year', 'now');
        $countryCodes = ['+1', '+57', '+52', '+34', '+44', '+33', '+49', '+39', '+55', '+54'];
        $activityLevels = [1, 2, 3, 4];
        $goalTypes = ['cut', 'maintain', 'grow'];
        $macroCalculationModes = ['auto', 'manual', 'tdee', 'bmr'];

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->optional(0.7)->phoneNumber(),
            'birth_date' => $birthDate ? $birthDate->format('Y-m-d') : null,
            'gender' => $this->faker->optional(0.9)->randomElement(['male', 'female', 'other']),
            'height' => $this->faker->optional(0.8)->numberBetween(150, 200),
            'weight' => $this->faker->optional(0.8)->randomFloat(2, 40, 150),
            'country_code' => $this->faker->optional(0.7)->randomElement($countryCodes),
            'activity_level' => $this->faker->optional(0.8)->randomElement($activityLevels),
            'goal_type' => $this->faker->optional(0.8)->randomElement($goalTypes),
            'macro_calculation_mode' => $this->faker->optional(0.7)->randomElement($macroCalculationModes),
            'google_id' => null,
            'auth_provider' => null,
            'password' => static::$password ??= Hash::make('password'),
            'role_id' => 1,
            'email_verified_at' => $emailVerifiedAt,
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
                'weight' => $this->faker->randomFloat(2, 60, 100),
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
                'weight' => $this->faker->randomFloat(2, 45, 80),
            ];
        });
    }
}
