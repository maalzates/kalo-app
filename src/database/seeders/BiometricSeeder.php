<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Biometric;
use App\Models\User;
use Illuminate\Database\Seeder;

class BiometricSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Create weekly measurements for last 12 weeks using factory
            for ($week = 0; $week < 12; $week++) {
                Biometric::factory()->complete()->create([
                    'user_id' => $user->id,
                    'measured_at' => now()->subWeeks($week)->subHours(fake()->numberBetween(0, 23)),
                ]);
            }

            // Recent measurements
            Biometric::factory(3)->recent()->complete()->create([
                'user_id' => $user->id,
            ]);
        }
    }
}

