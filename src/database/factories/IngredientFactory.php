<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Ingredient\Infrastructure\Models\Ingredient;
use App\Modules\User\Infrastructure\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class IngredientFactory extends Factory
{
    protected $model = Ingredient::class;

    public function definition(): array
    {
        $unit = $this->faker->randomElement(['g', 'ml', 'un']);
        
        // Generate base amount
        $amount = $unit === 'un' 
            ? $this->faker->numberBetween(1, 3)
            : $this->faker->numberBetween(50, 200);

        // Generate calories (kcal) - realistic range
        $kcal = $this->faker->numberBetween(20, 600);
        
        // Generate protein (higher for some, lower for others)
        $prot = $this->faker->randomFloat(2, 0, 50);
        
        // Generate carbs
        $carb = $this->faker->randomFloat(2, 0, 70);
        
        // Generate fat
        $fat = $this->faker->randomFloat(2, 0, 50);

        $userIds = User::pluck('id')->toArray();
        $userId = $userIds && $this->faker->boolean(30) 
            ? $this->faker->randomElement($userIds) 
            : null;

        return [
            'name' => $this->faker->word() . ' ' . $this->faker->randomElement(['Food', 'Snack', 'Meal', 'Ingredient']),
            'amount' => $amount,
            'unit' => $unit,
            'kcal' => $kcal,
            'prot' => $prot,
            'carb' => $carb,
            'fat' => $fat,
            'user_id' => $userId,
        ];
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

    public function highProtein(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'prot' => $this->faker->randomFloat(2, 20, 35),
                'carb' => $this->faker->randomFloat(2, 0, 5),
                'fat' => $this->faker->randomFloat(2, 2, 10),
                'kcal' => $this->faker->numberBetween(120, 200),
            ];
        });
    }

    public function highCarb(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'prot' => $this->faker->randomFloat(2, 2, 8),
                'carb' => $this->faker->randomFloat(2, 40, 70),
                'fat' => $this->faker->randomFloat(2, 0, 3),
                'kcal' => $this->faker->numberBetween(200, 400),
            ];
        });
    }

    public function highFat(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'prot' => $this->faker->randomFloat(2, 5, 15),
                'carb' => $this->faker->randomFloat(2, 0, 10),
                'fat' => $this->faker->randomFloat(2, 30, 50),
                'kcal' => $this->faker->numberBetween(400, 600),
            ];
        });
    }

    public function lowCalorie(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'prot' => $this->faker->randomFloat(2, 1, 5),
                'carb' => $this->faker->randomFloat(2, 2, 10),
                'fat' => $this->faker->randomFloat(2, 0, 2),
                'kcal' => $this->faker->numberBetween(20, 60),
            ];
        });
    }
}

