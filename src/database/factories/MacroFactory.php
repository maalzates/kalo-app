<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Macro;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MacroFactory extends Factory
{
    protected $model = Macro::class;

    public function definition(): array
    {
        $kcal = $this->faker->numberBetween(1500, 3500);
        
        // Generate protein (typically 1.6-2.2g per kg, assume 70kg average)
        $prot = $this->faker->randomFloat(2, 100, 200);
        
        // Generate fat (25-35% of calories, 9 kcal per gram)
        $fatPercentage = $this->faker->randomFloat(2, 0.25, 0.35);
        $fatCalories = $kcal * $fatPercentage;
        $fat = round($fatCalories / 9, 2);
        
        // Generate carbs from remaining calories (4 kcal per gram)
        $protCalories = $prot * 4;
        $carbCalories = $kcal - $protCalories - $fatCalories;
        $carb = round(max(0, $carbCalories / 4), 2);

        return [
            'kcal' => $kcal,
            'prot' => $prot,
            'carb' => max(0, $carb),
            'fat' => $fat,
            'user_id' => User::factory(),
        ];
    }

    public function cutting(): static
    {
        return $this->state(function (array $attributes) {
            $kcal = $this->faker->numberBetween(1500, 2200);
            $prot = $this->faker->randomFloat(2, 120, 180);
            $fatCalories = $kcal * 0.30;
            $fat = round($fatCalories / 9, 2);
            $carb = round(($kcal - ($prot * 4) - $fatCalories) / 4, 2);
            
            return [
                'kcal' => $kcal,
                'prot' => $prot,
                'fat' => $fat,
                'carb' => max(0, $carb),
            ];
        });
    }

    public function bulking(): static
    {
        return $this->state(function (array $attributes) {
            $kcal = $this->faker->numberBetween(2800, 3800);
            $prot = $this->faker->randomFloat(2, 140, 200);
            $fatCalories = $kcal * 0.28;
            $fat = round($fatCalories / 9, 2);
            $carb = round(($kcal - ($prot * 4) - $fatCalories) / 4, 2);
            
            return [
                'kcal' => $kcal,
                'prot' => $prot,
                'fat' => $fat,
                'carb' => max(0, $carb),
            ];
        });
    }

    public function maintenance(): static
    {
        return $this->state(function (array $attributes) {
            $kcal = $this->faker->numberBetween(2200, 2800);
            $prot = $this->faker->randomFloat(2, 130, 170);
            $fatCalories = $kcal * 0.30;
            $fat = round($fatCalories / 9, 2);
            $carb = round(($kcal - ($prot * 4) - $fatCalories) / 4, 2);
            
            return [
                'kcal' => $kcal,
                'prot' => $prot,
                'fat' => $fat,
                'carb' => max(0, $carb),
            ];
        });
    }
}

