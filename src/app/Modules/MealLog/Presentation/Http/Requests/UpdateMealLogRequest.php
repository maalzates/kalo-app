<?php

declare(strict_types=1);

namespace App\Modules\MealLog\Presentation\Http\Requests;

use App\Modules\MealLog\Application\DTOs\UpdateMealLogDTO;
use App\Models\MealLog;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMealLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'meal_log_id' => $this->route('mealLog')->id ?? $this->route('mealLog'),
        ]);
    }

    public function rules(): array
    {
        return [
            'ingredient_id' => [
                'nullable',
                'exists:ingredients,id',
            ],
            'recipe_id' => [
                'nullable',
                'exists:recipes,id',
            ],
            'quantity' => ['sometimes', 'numeric', 'min:0.01'],
            'unit' => ['sometimes', 'in:g,ml,un,serving'],
            'logged_at' => ['nullable', 'date'],
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Verificar que no se proporcionen ambos campos
            if ($this->has('ingredient_id') && $this->has('recipe_id')) {
                $validator->errors()->add('ingredient_id', 'Cannot provide both ingredient_id and recipe_id.');
                $validator->errors()->add('recipe_id', 'Cannot provide both ingredient_id and recipe_id.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'ingredient_id.exists' => 'The selected ingredient does not exist.',
            'recipe_id.exists' => 'The selected recipe does not exist.',
            'quantity.numeric' => 'The quantity must be a number.',
            'quantity.min' => 'The quantity must be at least 0.01.',
            'unit.in' => 'The unit must be one of: g, ml, un, serving.',
            'logged_at.date' => 'The logged_at must be a valid date.',
        ];
    }

    public function toDTO(): UpdateMealLogDTO
    {
        $mealLog = $this->route('mealLog');
        $mealLogId = $mealLog instanceof MealLog ? (string) $mealLog->id : (string) $mealLog;

        return new UpdateMealLogDTO(
            mealLogId: $mealLogId,
            ingredientId: $this->input('ingredient_id') ? (string) $this->input('ingredient_id') : null,
            recipeId: $this->input('recipe_id') ? (string) $this->input('recipe_id') : null,
            quantity: $this->input('quantity') ? (string) $this->input('quantity') : null,
            unit: $this->input('unit'),
            loggedAt: $this->input('logged_at'),
        );
    }
}

