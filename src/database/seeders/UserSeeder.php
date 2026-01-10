<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar roles por nombre
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();
        $premiumRole = Role::where('name', 'premium_user')->first();

        // Crear usuarios específicos con updateOrCreate para evitar duplicados
        User::updateOrCreate(
            ['email' => 'admin@kalo.tld'],
            [
                'name' => 'Admin User',
                'email' => 'admin@kalo.tld',
                'password' => Hash::make('root'),
                'role_id' => $adminRole->id ?? 2,
                'email_verified_at' => now(),
                'phone' => '1234567890',
                'birth_date' => '1985-03-15',
                'gender' => 'male',
                'height' => 180,
                'weight' => 75.50,
                'country_code' => '+57',
                'activity_level' => 3,
                'goal_type' => 'maintain',
                'macro_calculation_mode' => 'auto',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@kalo.tld'],
            [
                'name' => 'Regular User',
                'email' => 'user@kalo.tld',
                'password' => Hash::make('root'),
                'role_id' => $userRole->id ?? 1,
                'email_verified_at' => now(),
                'phone' => '1234567891',
                'birth_date' => '1990-07-22',
                'gender' => 'female',
                'height' => 165,
                'weight' => 70.25,
                'country_code' => '+57',
                'activity_level' => 2,
                'goal_type' => 'cut',
                'macro_calculation_mode' => 'tdee',
            ]
        );

        User::updateOrCreate(
            ['email' => 'premium@kalo.tld'],
            [
                'name' => 'Premium User',
                'email' => 'premium@kalo.tld',
                'password' => Hash::make('root'),
                'role_id' => $premiumRole->id ?? 4,
                'email_verified_at' => now(),
                'phone' => '1234567892',
                'birth_date' => '1992-11-08',
                'gender' => 'male',
                'height' => 175,
                'weight' => 80.00,
                'country_code' => '+57',
                'activity_level' => 4,
                'goal_type' => 'grow',
                'macro_calculation_mode' => 'manual',
            ]
        );

        // Crear solo 5 usuarios adicionales
        User::factory(5)->create();
    }
}

