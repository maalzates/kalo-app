<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Order is critical - respect foreign key constraints
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            PermissionRoleSeeder::class,
            UserSeeder::class,
            IngredientSeeder::class,
            RecipeSeeder::class,
            MealLogSeeder::class,
            BiometricSeeder::class,
            MacroSeeder::class,
        ]);
    }
}

