<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\MealLog\Infrastructure\Models\MealLog;
use App\Modules\User\Infrastructure\Models\User;
use App\Modules\Ingredient\Infrastructure\Models\Ingredient;
use App\Modules\Recipe\Infrastructure\Models\Recipe;
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

            // Logs from last 30 days
            for ($day = 0; $day < 30; $day++) {
                $mealsPerDay = fake()->numberBetween(3, 6);

                for ($meal = 0; $meal < $mealsPerDay; $meal++) {
                    $useRecipe = $userRecipes->isNotEmpty() && fake()->boolean(40);

                    if ($useRecipe) {
                        MealLog::factory()->recipe()->create([
                            'user_id' => $user->id,
                            'recipe_id' => $userRecipes->random()->id,
                            'logged_at' => now()->subDays($day)->addHours(fake()->numberBetween(6, 22)),
                        ]);
                    } else {
                        MealLog::factory()->ingredient()->create([
                            'user_id' => $user->id,
                            'ingredient_id' => $globalIngredients->random()->id,
                            'logged_at' => now()->subDays($day)->addHours(fake()->numberBetween(6, 22)),
                        ]);
                    }
                }
            }

            // Some recent logs (this week)
            MealLog::factory(5)->thisWeek()->create([
                'user_id' => $user->id,
                'ingredient_id' => $globalIngredients->random()->id,
            ]);

            // Some today logs
            MealLog::factory(2)->today()->create([
                'user_id' => $user->id,
                'ingredient_id' => $globalIngredients->random()->id,
            ]);
        }
    }
}

