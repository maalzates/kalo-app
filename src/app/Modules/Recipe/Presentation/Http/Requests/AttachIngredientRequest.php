<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Presentation\Http\Requests;

use App\Modules\Recipe\Application\DTOs\AttachIngredientDTO;
use App\Models\Recipe;
use Illuminate\Foundation\Http\FormRequest;

class AttachIngredientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'recipe_id' => $this->route('recipe')->id ?? $this->route('recipe'),
        ]);
    }

    public function rules(): array
    {
        return [
            'ingredient_id' => ['required', 'exists:ingredients,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['required', 'in:g,ml,un'],
        ];
    }

    public function messages(): array
    {
        return [
            'ingredient_id.required' => 'The ingredient_id field is required.',
            'ingredient_id.exists' => 'The selected ingredient does not exist.',
            'amount.required' => 'The amount field is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least 0.01.',
            'unit.required' => 'The unit field is required.',
            'unit.in' => 'The unit must be one of: g, ml, un.',
        ];
    }

    public function toDTO(): AttachIngredientDTO
    {
        $recipe = $this->route('recipe');
        $recipeId = $recipe instanceof Recipe ? (string) $recipe->id : (string) $recipe;

        return new AttachIngredientDTO(
            recipeId: $recipeId,
            ingredientId: (string) $this->input('ingredient_id'),
            amount: (string) $this->input('amount'),
            unit: $this->input('unit'),
        );
    }
}

