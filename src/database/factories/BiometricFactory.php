<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Biometric\Infrastructure\Models\Biometric;
use App\Modules\User\Infrastructure\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BiometricFactory extends Factory
{
    protected $model = Biometric::class;

    public function definition(): array
    {
        $weight = $this->faker->randomFloat(2, 55, 120);
        $fatPercentage = $this->faker->optional(0.8)->randomFloat(2, 8, 35);
        $cleanMass = $fatPercentage 
            ? round($weight * (1 - $fatPercentage / 100), 2)
            : null;

        return [
            'user_id' => User::factory(),
            'weight' => $weight,
            'fat_percentage' => $fatPercentage,
            'clean_mass' => $cleanMass,
            'waist_circumference' => $this->faker->optional(0.7)->randomFloat(2, 60, 120),
            'measured_at' => $this->faker->dateTimeBetween('-90 days', 'now'),
        ];
    }

    public function complete(): static
    {
        return $this->state(function (array $attributes) {
            $weight = $this->faker->randomFloat(2, 60, 110);
            $fatPercentage = $this->faker->randomFloat(2, 10, 30);
            
            return [
                'weight' => $weight,
                'fat_percentage' => $fatPercentage,
                'clean_mass' => round($weight * (1 - $fatPercentage / 100), 2),
                'waist_circumference' => $this->faker->randomFloat(2, 65, 110),
            ];
        });
    }

    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'measured_at' => $this->faker->dateTimeBetween('-7 days', 'now'),
        ]);
    }
}

