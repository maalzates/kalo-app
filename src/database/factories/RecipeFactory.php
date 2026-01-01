<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    public function definition(): array
    {
        $servings = $this->faker->numberBetween(1, 6);
        
        // Generate realistic recipe macros
        $totalKcal = $this->faker->numberBetween(300, 800);
        $totalProt = $this->faker->randomFloat(2, 15, 60);
        $totalCarb = $this->faker->randomFloat(2, 20, 80);
        $totalFat = $this->faker->randomFloat(2, 5, 40);

        return [
            'name' => $this->faker->word() . ' ' . $this->faker->randomElement(['Bowl', 'Plate', 'Mix', 'Salad', 'Smoothie', 'Wrap']),
            'servings' => $servings,
            'total_kcal' => $totalKcal,
            'total_prot' => $totalProt,
            'total_carb' => $totalCarb,
            'total_fat' => $totalFat,
            'user_id' => User::factory(),
        ];
    }

    public function lowCalorie(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'total_kcal' => $this->faker->numberBetween(200, 400),
                'total_prot' => $this->faker->randomFloat(2, 15, 30),
                'total_carb' => $this->faker->randomFloat(2, 20, 40),
                'total_fat' => $this->faker->randomFloat(2, 5, 15),
            ];
        });
    }

    public function highProtein(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'total_kcal' => $this->faker->numberBetween(400, 700),
                'total_prot' => $this->faker->randomFloat(2, 40, 70),
                'total_carb' => $this->faker->randomFloat(2, 20, 50),
                'total_fat' => $this->faker->randomFloat(2, 10, 25),
            ];
        });
    }

    public function bulkMeal(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'total_kcal' => $this->faker->numberBetween(700, 1200),
                'total_prot' => $this->faker->randomFloat(2, 35, 60),
                'total_carb' => $this->faker->randomFloat(2, 60, 120),
                'total_fat' => $this->faker->randomFloat(2, 20, 45),
            ];
        });
    }

    public function global(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
        ]);
    }

    public function userSpecific(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => User::factory(),
        ]);
    }
}

