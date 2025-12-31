<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\MealLog;
use App\Models\User;
use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

class MealLogFactory extends Factory
{
    protected $model = MealLog::class;

    public function definition(): array
    {
        $isIngredient = $this->faker->boolean(60);

        return [
            'user_id' => User::factory(),
            'ingredient_id' => $isIngredient ? Ingredient::factory() : null,
            'recipe_id' => !$isIngredient ? Recipe::factory() : null,
            'quantity' => $this->faker->randomFloat(2, 50, 500),
            'unit' => $isIngredient 
                ? $this->faker->randomElement(['g', 'ml', 'un'])
                : 'serving',
            'logged_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }

    public function ingredient(): static
    {
        return $this->state(fn (array $attributes) => [
            'ingredient_id' => Ingredient::factory(),
            'recipe_id' => null,
            'quantity' => $this->faker->randomFloat(2, 50, 400),
            'unit' => $this->faker->randomElement(['g', 'ml', 'un']),
        ]);
    }

    public function recipe(): static
    {
        return $this->state(fn (array $attributes) => [
            'ingredient_id' => null,
            'recipe_id' => Recipe::factory(),
            'quantity' => $this->faker->numberBetween(1, 3),
            'unit' => 'serving',
        ]);
    }

    public function today(): static
    {
        return $this->state(fn (array $attributes) => [
            'logged_at' => now()->subHours($this->faker->numberBetween(1, 12)),
        ]);
    }

    public function thisWeek(): static
    {
        return $this->state(fn (array $attributes) => [
            'logged_at' => now()->subDays($this->faker->numberBetween(0, 7)),
        ]);
    }
}

