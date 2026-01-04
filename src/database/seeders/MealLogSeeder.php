<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\MealLog;
use App\Models\User;
use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Database\Seeder;

class MealLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $userRecipes = Recipe::where('user_id', $user->id)->get();
            $globalIngredients = Ingredient::whereNull('user_id')->get();

            if ($globalIngredients->isEmpty()) {
                continue;
            }

            // Logs from last 30 days (mezcla de ingredient, recipe y AI)
            for ($day = 0; $day < 30; $day++) {
                $mealsPerDay = fake()->numberBetween(3, 6);

                for ($meal = 0; $meal < $mealsPerDay; $meal++) {
                    $mealType = fake()->randomElement(['ingredient', 'recipe', 'ai_ingredient', 'ai_recipe']);
                    $loggedAt = now()->subDays($day)->addHours(fake()->numberBetween(6, 22));

                    if ($mealType === 'recipe' && $userRecipes->isNotEmpty()) {
                        MealLog::factory()->recipe()->create([
                            'user_id' => $user->id,
                            'recipe_id' => $userRecipes->random()->id,
                            'logged_at' => $loggedAt,
                            'created_at' => $loggedAt,
                            'updated_at' => $loggedAt,
                        ]);
                    } elseif ($mealType === 'ingredient') {
                        MealLog::factory()->ingredient()->create([
                            'user_id' => $user->id,
                            'ingredient_id' => $globalIngredients->random()->id,
                            'logged_at' => $loggedAt,
                            'created_at' => $loggedAt,
                            'updated_at' => $loggedAt,
                        ]);
                    } elseif ($mealType === 'ai_recipe') {
                        MealLog::factory()->aiRecipe()->create([
                            'user_id' => $user->id,
                            'logged_at' => $loggedAt,
                            'created_at' => $loggedAt,
                            'updated_at' => $loggedAt,
                        ]);
                    } else {
                        // ai_ingredient
                        MealLog::factory()->aiIngredient()->create([
                            'user_id' => $user->id,
                            'logged_at' => $loggedAt,
                            'created_at' => $loggedAt,
                            'updated_at' => $loggedAt,
                        ]);
                    }
                }
            }

            // Some recent logs (this week) - mezcla de tipos
            MealLog::factory(3)->thisWeek()->create([
                'user_id' => $user->id,
                'ingredient_id' => $globalIngredients->random()->id,
            ]);

            MealLog::factory(2)->thisWeek()->aiIngredient()->create([
                'user_id' => $user->id,
            ]);

            // Some today logs - mezcla de tipos
            MealLog::factory(1)->today()->create([
                'user_id' => $user->id,
                'ingredient_id' => $globalIngredients->random()->id,
            ]);

            MealLog::factory(1)->today()->aiIngredient()->create([
                'user_id' => $user->id,
            ]);

            // Asegurar logs específicos para 3 días atrás y hoy
            // 3 días atrás
            $threeDaysAgo = now()->subDays(3)->addHours(fake()->numberBetween(8, 20));
            MealLog::factory(2)->create([
                'user_id' => $user->id,
                'ingredient_id' => $globalIngredients->random()->id,
                'logged_at' => $threeDaysAgo,
                'created_at' => $threeDaysAgo,
                'updated_at' => $threeDaysAgo,
            ]);

            $threeDaysAgoRecipe = now()->subDays(3)->addHours(fake()->numberBetween(8, 20));
            MealLog::factory(1)->aiRecipe()->create([
                'user_id' => $user->id,
                'logged_at' => $threeDaysAgoRecipe,
                'created_at' => $threeDaysAgoRecipe,
                'updated_at' => $threeDaysAgoRecipe,
            ]);

            // Hoy
            $today = now()->subHours(fake()->numberBetween(1, 12));
            MealLog::factory(2)->create([
                'user_id' => $user->id,
                'ingredient_id' => $globalIngredients->random()->id,
                'logged_at' => $today,
                'created_at' => $today,
                'updated_at' => $today,
            ]);

            $todayAI = now()->subHours(fake()->numberBetween(1, 12));
            MealLog::factory(1)->aiIngredient()->create([
                'user_id' => $user->id,
                'logged_at' => $todayAI,
                'created_at' => $todayAI,
                'updated_at' => $todayAI,
            ]);
        }
    }
}

