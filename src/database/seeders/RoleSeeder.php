<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create system roles using factory states
        Role::factory()->user()->create(['id' => 1]);
        Role::factory()->admin()->create(['id' => 2]);
        Role::factory()->nutritionist()->create(['id' => 3]);
        Role::factory()->premiumUser()->create(['id' => 4]);
    }
}

