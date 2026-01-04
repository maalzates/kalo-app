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
        // Usar now() como base para que logged_at y created_at sean iguales por defecto
        $baseDate = now();

        return [
            'user_id' => User::factory(),
            'ingredient_id' => $isIngredient ? Ingredient::factory() : null,
            'recipe_id' => !$isIngredient ? Recipe::factory() : null,
            'quantity' => $this->faker->randomFloat(2, 50, 500),
            'unit' => $isIngredient 
                ? $this->faker->randomElement(['g', 'ml', 'un'])
                : 'serving',
            'logged_at' => $baseDate,
            'created_at' => $baseDate,
            'updated_at' => $baseDate,
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
        $date = now()->subHours($this->faker->numberBetween(1, 12));
        return $this->state(fn (array $attributes) => [
            'logged_at' => $date,
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }

    public function thisWeek(): static
    {
        $date = now()->subDays($this->faker->numberBetween(0, 7));
        return $this->state(fn (array $attributes) => [
            'logged_at' => $date,
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }

    public function aiIngredient(): static
    {
        $amount = $this->faker->randomFloat(2, 100, 500);
        $unit = $this->faker->randomElement(['g', 'ml', 'un']);
        $kcal = $this->faker->numberBetween(200, 800);
        $prot = $this->faker->randomFloat(1, 10, 50);
        $carb = $this->faker->randomFloat(1, 20, 80);
        $fat = $this->faker->randomFloat(1, 5, 40);

        return $this->state(fn (array $attributes) => [
            'ingredient_id' => null,
            'recipe_id' => null,
            'ai_name' => $this->faker->randomElement([
                'Pollo a la plancha',
                'Ensalada mixta',
                'Pescado al horno',
                'Arroz con verduras',
                'Salmón con brócoli',
                'Pasta integral',
                'Huevos revueltos',
                'Avena con frutas',
            ]),
            'ai_data' => [
                'type' => 'ingredient',
                'name' => $this->faker->randomElement([
                    'Pollo a la plancha',
                    'Ensalada mixta',
                    'Pescado al horno',
                    'Arroz con verduras',
                    'Salmón con brócoli',
                    'Pasta integral',
                    'Huevos revueltos',
                    'Avena con frutas',
                ]),
                'amount' => $amount,
                'unit' => $unit,
                'kcal' => $kcal,
                'prot' => $prot,
                'carb' => $carb,
                'fat' => $fat,
            ],
            'quantity' => $this->faker->randomFloat(2, 50, $amount * 2),
            'unit' => $unit,
        ]);
    }

    public function aiRecipe(): static
    {
        $servings = $this->faker->numberBetween(2, 6);
        $totalKcal = $this->faker->numberBetween(400, 1200);
        $totalProt = $this->faker->randomFloat(1, 20, 80);
        $totalCarb = $this->faker->randomFloat(1, 40, 150);
        $totalFat = $this->faker->randomFloat(1, 10, 60);

        $ingredients = [];
        $ingredientCount = $this->faker->numberBetween(2, 5);
        for ($i = 0; $i < $ingredientCount; $i++) {
            $ingredients[] = [
                'name' => $this->faker->randomElement([
                    'Pollo',
                    'Arroz',
                    'Brócoli',
                    'Aceite de oliva',
                    'Cebolla',
                    'Tomate',
                    'Queso',
                    'Pasta',
                ]),
                'amount' => $this->faker->randomFloat(2, 50, 300),
                'unit' => $this->faker->randomElement(['g', 'ml']),
                'kcal' => $this->faker->numberBetween(50, 300),
                'prot' => $this->faker->randomFloat(1, 5, 30),
                'carb' => $this->faker->randomFloat(1, 10, 50),
                'fat' => $this->faker->randomFloat(1, 2, 20),
            ];
        }

        return $this->state(fn (array $attributes) => [
            'ingredient_id' => null,
            'recipe_id' => null,
            'ai_name' => $this->faker->randomElement([
                'Pasta con pollo y verduras',
                'Ensalada completa',
                'Plato de pescado con guarnición',
                'Bowl de quinoa y vegetales',
                'Wrap saludable',
                'Risotto de verduras',
            ]),
            'ai_data' => [
                'type' => 'recipe',
                'name' => $this->faker->randomElement([
                    'Pasta con pollo y verduras',
                    'Ensalada completa',
                    'Plato de pescado con guarnición',
                    'Bowl de quinoa y vegetales',
                    'Wrap saludable',
                    'Risotto de verduras',
                ]),
                'servings' => $servings,
                'kcal' => $totalKcal,
                'prot' => $totalProt,
                'carb' => $totalCarb,
                'fat' => $totalFat,
                'ingredients' => $ingredients,
            ],
            'quantity' => $this->faker->randomFloat(1, 0.5, $servings),
            'unit' => 'serving',
        ]);
    }
}

