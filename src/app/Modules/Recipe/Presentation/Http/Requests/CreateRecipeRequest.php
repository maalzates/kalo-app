<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Presentation\Http\Requests;

use App\Modules\Recipe\Application\DTOs\CreateRecipeDTO;
use App\Modules\Recipe\Application\DTOs\RecipeIngredientDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateRecipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'servings' => ['required', 'integer', 'min:1'],
            'total_kcal' => ['required', 'integer', 'min:0'],
            'total_prot' => ['required', 'numeric', 'min:0'],
            'total_carb' => ['required', 'numeric', 'min:0'],
            'total_fat' => ['required', 'numeric', 'min:0'],
            'ingredients' => ['nullable', 'array'],
            'ingredients.*.ingredient_id' => ['required_with:ingredients', 'exists:ingredients,id'],
            'ingredients.*.amount' => ['required_with:ingredients', 'numeric', 'min:0.01'],
            'ingredients.*.unit' => ['required_with:ingredients', 'in:g,ml,un'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'servings.required' => 'The servings field is required.',
            'servings.integer' => 'The servings must be an integer.',
            'servings.min' => 'The servings must be at least 1.',
            'total_kcal.required' => 'The total_kcal field is required.',
            'total_kcal.integer' => 'The total_kcal must be an integer.',
            'total_kcal.min' => 'The total_kcal must be at least 0.',
            'total_prot.required' => 'The total_prot field is required.',
            'total_prot.numeric' => 'The total_prot must be a number.',
            'total_prot.min' => 'The total_prot must be at least 0.',
            'total_carb.required' => 'The total_carb field is required.',
            'total_carb.numeric' => 'The total_carb must be a number.',
            'total_carb.min' => 'The total_carb must be at least 0.',
            'total_fat.required' => 'The total_fat field is required.',
            'total_fat.numeric' => 'The total_fat must be a number.',
            'total_fat.min' => 'The total_fat must be at least 0.',
            'ingredients.*.ingredient_id.required_with' => 'Each ingredient must have an ingredient_id.',
            'ingredients.*.ingredient_id.exists' => 'One or more selected ingredients do not exist.',
            'ingredients.*.amount.required_with' => 'Each ingredient must have an amount.',
            'ingredients.*.amount.numeric' => 'Each ingredient amount must be a number.',
            'ingredients.*.amount.min' => 'Each ingredient amount must be at least 0.01.',
            'ingredients.*.unit.required_with' => 'Each ingredient must have a unit.',
            'ingredients.*.unit.in' => 'Each ingredient unit must be one of: g, ml, un.',
        ];
    }

    public function toDTO(): CreateRecipeDTO
    {
        $ingredients = null;
        if ($this->has('ingredients') && is_array($this->input('ingredients'))) {
            $ingredients = array_map(function ($ingredient) {
                return new RecipeIngredientDTO(
                    ingredientId: (string) $ingredient['ingredient_id'],
                    amount: (string) $ingredient['amount'],
                    unit: $ingredient['unit'],
                );
            }, $this->input('ingredients'));
        }

        return new CreateRecipeDTO(
            name: $this->input('name'),
            servings: (int) $this->input('servings'),
            totalKcal: (int) $this->input('total_kcal'),
            totalProt: (string) $this->input('total_prot'),
            totalCarb: (string) $this->input('total_carb'),
            totalFat: (string) $this->input('total_fat'),
            userId: (string) auth()->id(),
            ingredients: $ingredients,
        );
    }
}

