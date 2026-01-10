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
            $usedDates = [];

            // Create weekly measurements for last 12 weeks using factory
            // Solo un registro por semana, asegurando fechas únicas por día
            for ($week = 0; $week < 12; $week++) {
                $measuredAt = now()->subWeeks($week)->startOfDay();
                $dateKey = $measuredAt->format('Y-m-d');

                // Solo crear si no existe ya un registro para esta fecha
                if (!in_array($dateKey, $usedDates)) {
                    Biometric::factory()->complete()->create([
                        'user_id' => $user->id,
                        'measured_at' => $measuredAt,
                    ]);
                    $usedDates[] = $dateKey;
                }
            }
        }
    }
}

