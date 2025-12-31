<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Macro\Infrastructure\Models\Macro;
use App\Modules\User\Infrastructure\Models\User;
use Illuminate\Database\Seeder;

class MacroSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Randomly assign macro goals using factory states
            $macroType = fake()->randomElement(['cutting', 'bulking', 'maintenance', 'default']);

            if ($macroType === 'cutting') {
                Macro::factory()->cutting()->create(['user_id' => $user->id]);
            } elseif ($macroType === 'bulking') {
                Macro::factory()->bulking()->create(['user_id' => $user->id]);
            } elseif ($macroType === 'maintenance') {
                Macro::factory()->maintenance()->create(['user_id' => $user->id]);
            } else {
                Macro::factory()->create(['user_id' => $user->id]);
            }
        }
    }
}

