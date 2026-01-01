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
            // Crear dos macros por usuario: uno de hoy y otro de ayer
            // Macro de ayer (más antiguo)
            $macroTypeYesterday = fake()->randomElement(['cutting', 'bulking', 'maintenance', 'default']);
            $macroYesterday = $this->createMacroByType($macroTypeYesterday, $user->id);
            $macroYesterday->created_at = now()->subDay();
            $macroYesterday->updated_at = now()->subDay();
            $macroYesterday->save();

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

