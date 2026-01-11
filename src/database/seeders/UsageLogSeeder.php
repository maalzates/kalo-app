<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Logs\Infrastructure\Persistence\Eloquent\UsageLogModel;
use Illuminate\Database\Seeder;

class UsageLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Skipping UsageLog seeding.');

            return;
        }

        foreach ($users as $user) {
            // Create successful logs from this week
            UsageLogModel::factory()
                ->count(15)
                ->for($user)
                ->thisWeek()
                ->success()
                ->create();

            // Create some failed logs
            UsageLogModel::factory()
                ->count(2)
                ->for($user)
                ->thisWeek()
                ->failed()
                ->create();

            // Create logs from this month with different providers
            UsageLogModel::factory()
                ->count(5)
                ->for($user)
                ->thisMonth()
                ->gemini()
                ->create();

            UsageLogModel::factory()
                ->count(3)
                ->for($user)
                ->thisMonth()
                ->openai()
                ->create();

            // Create logs with specific food types
            UsageLogModel::factory()
                ->count(10)
                ->for($user)
                ->thisMonth()
                ->ingredient()
                ->create();

            UsageLogModel::factory()
                ->count(5)
                ->for($user)
                ->thisMonth()
                ->recipe()
                ->create();
        }

        $this->command->info('UsageLog seeded successfully!');
    }
}
