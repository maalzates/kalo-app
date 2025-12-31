<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Role\Infrastructure\Models\Role;
use App\Modules\Permission\Infrastructure\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    public function run(): void
    {
        $userRole = Role::find(1);
        $adminRole = Role::find(2);
        $nutritionistRole = Role::find(3);
        $premiumRole = Role::find(4);

        // User role - basic permissions
        $userPermissions = Permission::where('name', 'like', 'view_ingredients')
            ->orWhere('name', 'like', 'create_ingredients')
            ->orWhere('name', 'like', 'edit_ingredients')
            ->orWhere('name', 'like', 'delete_ingredients')
            ->orWhere('name', 'like', 'view_recipes')
            ->orWhere('name', 'like', 'create_recipes')
            ->orWhere('name', 'like', 'edit_recipes')
            ->orWhere('name', 'like', 'delete_recipes')
            ->orWhere('name', 'like', 'view_meal_logs')
            ->orWhere('name', 'like', 'create_meal_logs')
            ->orWhere('name', 'like', 'edit_meal_logs')
            ->orWhere('name', 'like', 'delete_meal_logs')
            ->orWhere('name', 'like', 'view_biometrics')
            ->orWhere('name', 'like', 'create_biometrics')
            ->orWhere('name', 'like', 'edit_biometrics')
            ->orWhere('name', 'like', 'delete_biometrics')
            ->orWhere('name', 'like', 'view_macros')
            ->orWhere('name', 'like', 'create_macros')
            ->orWhere('name', 'like', 'edit_macros')
            ->orWhere('name', 'like', 'delete_macros')
            ->get();

        $userRole->permissions()->attach($userPermissions->pluck('id'));

        // Admin role - all permissions
        $adminRole->permissions()->attach(Permission::all()->pluck('id'));

        // Nutritionist role
        $nutritionistPermissions = Permission::where('name', 'like', 'view_%')
            ->orWhere('name', 'like', '%_ingredients')
            ->orWhere('name', 'like', '%_recipes')
            ->orWhere('name', 'like', '%_macros')
            ->orWhere('name', 'view_reports')
            ->get();

        $nutritionistRole->permissions()->attach($nutritionistPermissions->pluck('id'));

        // Premium user role
        $premiumPermissions = Permission::whereIn('name', [
            'view_ingredients', 'create_ingredients', 'edit_ingredients', 'delete_ingredients',
            'view_recipes', 'create_recipes', 'edit_recipes', 'delete_recipes',
            'view_meal_logs', 'create_meal_logs', 'edit_meal_logs', 'delete_meal_logs',
            'view_biometrics', 'create_biometrics', 'edit_biometrics', 'delete_biometrics',
            'view_macros', 'create_macros', 'edit_macros', 'delete_macros',
            'view_reports', 'export_data',
        ])->get();

        $premiumRole->permissions()->attach($premiumPermissions->pluck('id'));
    }
}

