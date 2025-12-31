<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Ingredient\Infrastructure\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        // Global ingredients using factories
        Ingredient::factory(15)->global()->create();
        
        // High protein ingredients
        Ingredient::factory(8)->global()->highProtein()->create();
        
        // High carb ingredients
        Ingredient::factory(7)->global()->highCarb()->create();
        
        // High fat ingredients
        Ingredient::factory(5)->global()->highFat()->create();
        
        // Low calorie ingredients
        Ingredient::factory(10)->global()->lowCalorie()->create();

        // User-specific ingredients
        Ingredient::factory(30)->userSpecific()->create();
    }
}

