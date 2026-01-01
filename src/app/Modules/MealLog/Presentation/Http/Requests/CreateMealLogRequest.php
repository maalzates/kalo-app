<?php

declare(strict_types=1);

namespace App\Modules\MealLog\Presentation\Http\Requests;

use App\Modules\MealLog\Application\DTOs\CreateMealLogDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateMealLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ingredient_id' => [
                'nullable',
                'required_without:recipe_id',
                'prohibited_with:recipe_id',
                'exists:ingredients,id',
            ],
            'recipe_id' => [
                'nullable',
                'required_without:ingredient_id',
                'prohibited_with:ingredient_id',
                'exists:recipes,id',
            ],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['required', 'in:g,ml,un,serving'],
            'logged_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'ingredient_id.required_without' => 'Either ingredient_id or recipe_id is required.',
            'ingredient_id.prohibited_with' => 'Cannot provide both ingredient_id and recipe_id.',
            'ingredient_id.exists' => 'The selected ingredient does not exist.',
            'recipe_id.required_without' => 'Either ingredient_id or recipe_id is required.',
            'recipe_id.prohibited_with' => 'Cannot provide both ingredient_id and recipe_id.',
            'recipe_id.exists' => 'The selected recipe does not exist.',
            'quantity.required' => 'The quantity field is required.',
            'quantity.numeric' => 'The quantity must be a number.',
            'quantity.min' => 'The quantity must be at least 0.01.',
            'unit.required' => 'The unit field is required.',
            'unit.in' => 'The unit must be one of: g, ml, un, serving.',
            'logged_at.date' => 'The logged_at must be a valid date.',
        ];
    }

    public function toDTO(): CreateMealLogDTO
    {
        return new CreateMealLogDTO(
            userId: (string) auth()->id(),
            ingredientId: $this->input('ingredient_id') ? (string) $this->input('ingredient_id') : null,
            recipeId: $this->input('recipe_id') ? (string) $this->input('recipe_id') : null,
            quantity: (string) $this->input('quantity'),
            unit: $this->input('unit'),
            loggedAt: $this->input('logged_at'),
        );
    }
}

