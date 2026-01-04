<?php

declare(strict_types=1);

namespace App\Modules\MealLog\Presentation\Http\Requests;

use App\Modules\MealLog\Application\DTOs\CreateMealLogFromAIDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateMealLogFromAIRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Validaciones condicionales según el tipo
        if ($this->input('ai_data.type') === 'ingredient') {
            $this->merge([
                'ai_data.amount' => $this->input('ai_data.amount'),
                'ai_data.unit' => $this->input('ai_data.unit'),
            ]);
        } elseif ($this->input('ai_data.type') === 'recipe') {
            $this->merge([
                'ai_data.servings' => $this->input('ai_data.servings'),
                'ai_data.ingredients' => $this->input('ai_data.ingredients', []),
            ]);
        }
    }

    public function rules(): array
    {
        $rules = [
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['required', 'in:g,ml,un,serving'],
            'ai_name' => ['required', 'string', 'max:255'],
            'ai_data' => ['required', 'array'],
            'ai_data.type' => ['required', 'in:ingredient,recipe'],
            'ai_data.name' => ['required', 'string'],
            'ai_data.kcal' => ['required', 'numeric', 'min:0'],
            'ai_data.prot' => ['required', 'numeric', 'min:0'],
            'ai_data.carb' => ['required', 'numeric', 'min:0'],
            'ai_data.fat' => ['required', 'numeric', 'min:0'],
            'logged_at' => ['nullable', 'date'],
        ];

        // Reglas condicionales para ingredient
        if ($this->input('ai_data.type') === 'ingredient') {
            $rules['ai_data.amount'] = ['required', 'numeric', 'min:0.01'];
            $rules['ai_data.unit'] = ['required', 'in:g,ml,un'];
        }

        // Reglas condicionales para recipe
        if ($this->input('ai_data.type') === 'recipe') {
            $rules['ai_data.servings'] = ['required', 'numeric', 'min:0.01'];
            $rules['ai_data.ingredients'] = ['nullable', 'array'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'The quantity field is required.',
            'quantity.numeric' => 'The quantity must be a number.',
            'quantity.min' => 'The quantity must be at least 0.01.',
            'unit.required' => 'The unit field is required.',
            'unit.in' => 'The unit must be one of: g, ml, un, serving.',
            'ai_name.required' => 'The AI name field is required.',
            'ai_name.string' => 'The AI name must be a string.',
            'ai_name.max' => 'The AI name may not be greater than 255 characters.',
            'ai_data.required' => 'The AI data field is required.',
            'ai_data.array' => 'The AI data must be an array.',
            'ai_data.type.required' => 'The AI data type is required.',
            'ai_data.type.in' => 'The AI data type must be either ingredient or recipe.',
            'ai_data.name.required' => 'The AI data name is required.',
            'ai_data.kcal.required' => 'The calories field is required.',
            'ai_data.kcal.numeric' => 'The calories must be a number.',
            'ai_data.prot.required' => 'The protein field is required.',
            'ai_data.prot.numeric' => 'The protein must be a number.',
            'ai_data.carb.required' => 'The carbs field is required.',
            'ai_data.carb.numeric' => 'The carbs must be a number.',
            'ai_data.fat.required' => 'The fat field is required.',
            'ai_data.fat.numeric' => 'The fat must be a number.',
            'ai_data.amount.required' => 'The amount field is required for ingredient type.',
            'ai_data.amount.numeric' => 'The amount must be a number.',
            'ai_data.servings.required' => 'The servings field is required for recipe type.',
            'ai_data.servings.numeric' => 'The servings must be a number.',
            'logged_at.date' => 'The logged_at must be a valid date.',
        ];
    }

    public function toDTO(): CreateMealLogFromAIDTO
    {
        return new CreateMealLogFromAIDTO(
            userId: (string) auth()->id(),
            quantity: (string) $this->input('quantity'),
            unit: $this->input('unit'),
            aiName: $this->input('ai_name'),
            aiData: $this->input('ai_data'),
            loggedAt: $this->input('logged_at'),
        );
    }
}

