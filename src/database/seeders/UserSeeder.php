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
                'weight' => 75.50,
                'goal_type' => 'maintain',
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
                'weight' => 70.25,
                'goal_type' => 'cut',
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
                'weight' => 80.00,
                'goal_type' => 'grow',
            ]
        );

        // Crear usuarios aleatorios usando factory
        User::factory(15)->create();

        // Crear usuarios premium aleatorios
        User::factory(5)->withRole($premiumRole->id ?? 4)->create();

        // Crear usuarios con Google auth
        User::factory(3)->withGoogle()->create();

        // Crear usuarios específicos por género
        User::factory(5)->male()->create();
        User::factory(5)->female()->create();
    }
}

