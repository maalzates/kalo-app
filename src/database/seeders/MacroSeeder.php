<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Macro;
use App\Models\User;
use Illuminate\Database\Seeder;

class MacroSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Crear macros: uno de 3 días atrás y otro de hoy
            // Macro de 3 días atrás (más antiguo)
            $macroTypeThreeDaysAgo = fake()->randomElement(['cutting', 'bulking', 'maintenance', 'default']);
            $macroThreeDaysAgo = $this->createMacroByType($macroTypeThreeDaysAgo, $user->id);
            $macroThreeDaysAgo->created_at = now()->subDays(3);
            $macroThreeDaysAgo->updated_at = now()->subDays(3);
            $macroThreeDaysAgo->save();

            // Macro de hoy (más reciente)
            $macroTypeToday = fake()->randomElement(['cutting', 'bulking', 'maintenance', 'default']);
            $macroToday = $this->createMacroByType($macroTypeToday, $user->id);
            $macroToday->created_at = now();
            $macroToday->updated_at = now();
            $macroToday->save();
        }
    }

    private function createMacroByType(string $type, int $userId): Macro
    {
        return match ($type) {
            'cutting' => Macro::factory()->cutting()->make(['user_id' => $userId]),
            'bulking' => Macro::factory()->bulking()->make(['user_id' => $userId]),
            'maintenance' => Macro::factory()->maintenance()->make(['user_id' => $userId]),
            default => Macro::factory()->make(['user_id' => $userId]),
        };
    }
}

