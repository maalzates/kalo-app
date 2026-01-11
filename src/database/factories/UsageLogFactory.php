<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Modules\Logs\Infrastructure\Persistence\Eloquent\UsageLogModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsageLogFactory extends Factory
{
    protected $model = UsageLogModel::class;

    public function definition(): array
    {
        $provider = $this->faker->randomElement(['gemini', 'openai', 'openrouter']);
        $foodType = $this->faker->randomElement(['ingredient', 'recipe']);
        $inputTokens = $this->faker->numberBetween(100, 1000);
        $outputTokens = $this->faker->numberBetween(200, 500);

        return [
            'user_id' => User::factory(),
            'ai_provider' => $provider,
            'action_type' => 'food_analysis',
            'food_type' => $foodType,
            'image_size_bytes' => $this->faker->numberBetween(50000, 500000),
            'input_tokens' => $inputTokens,
            'output_tokens' => $outputTokens,
            'total_tokens' => $inputTokens + $outputTokens,
            'cost_usd' => $this->faker->randomFloat(6, 0.001, 0.05),
            'request_duration_ms' => $this->faker->numberBetween(800, 5000),
            'status' => 'success',
            'error_message' => null,
            'metadata' => null,
        ];
    }

    public function success(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'success',
            'error_message' => null,
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'error',
            'error_message' => $this->faker->randomElement([
                'API rate limit exceeded',
                'Invalid image format',
                'Network timeout',
                'Authentication failed',
            ]),
            'input_tokens' => null,
            'output_tokens' => null,
            'total_tokens' => null,
            'cost_usd' => null,
        ]);
    }

    public function gemini(): static
    {
        return $this->state(fn (array $attributes) => [
            'ai_provider' => 'gemini',
        ]);
    }

    public function openai(): static
    {
        return $this->state(fn (array $attributes) => [
            'ai_provider' => 'openai',
        ]);
    }

    public function openrouter(): static
    {
        return $this->state(fn (array $attributes) => [
            'ai_provider' => 'openrouter',
        ]);
    }

    public function ingredient(): static
    {
        return $this->state(fn (array $attributes) => [
            'food_type' => 'ingredient',
        ]);
    }

    public function recipe(): static
    {
        return $this->state(fn (array $attributes) => [
            'food_type' => 'recipe',
        ]);
    }

    public function today(): static
    {
        $date = now()->subHours($this->faker->numberBetween(1, 12));

        return $this->state(fn (array $attributes) => [
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }

    public function thisWeek(): static
    {
        $date = now()->subDays($this->faker->numberBetween(0, 7));

        return $this->state(fn (array $attributes) => [
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }

    public function thisMonth(): static
    {
        $date = now()->subDays($this->faker->numberBetween(0, 30));

        return $this->state(fn (array $attributes) => [
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }
}
