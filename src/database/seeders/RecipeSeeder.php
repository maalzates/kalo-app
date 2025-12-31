<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $globalIngredients = Ingredient::whereNull('user_id')->get();

        if ($globalIngredients->isEmpty()) {
            return;
        }

        foreach ($users->take(20) as $user) {
            // Create various recipes using factory states
            Recipe::factory(2)->create(['user_id' => $user->id])
                ->each(function ($recipe) use ($globalIngredients) {
                    $ingredientCount = fake()->numberBetween(3, 6);
                    $selectedIngredients = $globalIngredients->random(min($ingredientCount, $globalIngredients->count()));

                    foreach ($selectedIngredients as $ingredient) {
                        $recipe->ingredients()->attach($ingredient->id, [
                            'amount' => fake()->randomFloat(2, 50, 300),
                            'unit' => $ingredient->unit,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                });

            // High protein recipes
            Recipe::factory(1)->highProtein()->create(['user_id' => $user->id])
                ->each(function ($recipe) use ($globalIngredients) {
                    $proteinIngredients = $globalIngredients->random(min(4, $globalIngredients->count()));
                    foreach ($proteinIngredients as $ingredient) {
                        $recipe->ingredients()->attach($ingredient->id, [
                            'amount' => fake()->randomFloat(2, 80, 250),
                            'unit' => $ingredient->unit,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                });

            // Low calorie recipes
            Recipe::factory(1)->lowCalorie()->create(['user_id' => $user->id])
                ->each(function ($recipe) use ($globalIngredients) {
                    $ingredients = $globalIngredients->random(min(3, $globalIngredients->count()));
                    foreach ($ingredients as $ingredient) {
                        $recipe->ingredients()->attach($ingredient->id, [
                            'amount' => fake()->randomFloat(2, 30, 150),
                            'unit' => $ingredient->unit,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                });
        }
    }
}

