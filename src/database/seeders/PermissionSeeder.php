<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $resources = ['users', 'ingredients', 'recipes', 'meal_logs', 'biometrics', 'macros', 'roles', 'permissions'];
        $actions = ['view', 'create', 'edit', 'delete'];
        
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::factory()->crud($resource, $action)->create();
            }
        }

        // Special permissions using factory
        Permission::factory()->crud('system', 'manage')->create();
        Permission::factory()->crud('reports', 'view')->create();
        Permission::factory()->crud('data', 'export')->create();
    }
}

