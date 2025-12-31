<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\User\Infrastructure\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user (known credentials for testing)
        User::factory()->withRole(2)->create([
            'name' => 'Admin User',
            'email' => 'admin@nutrition.test',
        ]);

        // Create nutritionist user
        User::factory()->withRole(3)->create([
            'name' => 'Nutritionist User',
            'email' => 'nutritionist@nutrition.test',
        ]);

        // Create regular users using factory
        User::factory(15)->create();

        // Create premium users using factory
        User::factory(5)->withRole(4)->create();

        // Create users with Google auth
        User::factory(3)->withGoogle()->create();

        // Create gender-specific users
        User::factory(5)->male()->create();
        User::factory(5)->female()->create();
    }
}

