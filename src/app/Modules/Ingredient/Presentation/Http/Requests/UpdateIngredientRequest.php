<?php

declare(strict_types=1);

namespace App\Modules\Ingredient\Presentation\Http\Requests;

use App\Modules\Ingredient\Application\DTOs\UpdateIngredientDTO;
use App\Models\Ingredient;
use Illuminate\Foundation\Http\FormRequest;

class UpdateIngredientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'ingredient_id' => $this->route('ingredient')->id ?? $this->route('ingredient'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'amount' => ['sometimes', 'numeric', 'min:0.01'],
            'unit' => ['sometimes', 'in:g,ml,un'],
            'kcal' => ['sometimes', 'integer', 'min:0'],
            'prot' => ['sometimes', 'numeric', 'min:0'],
            'carb' => ['sometimes', 'numeric', 'min:0'],
            'fat' => ['sometimes', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least 0.01.',
            'unit.in' => 'The unit must be one of: g, ml, un.',
            'kcal.integer' => 'The kcal must be an integer.',
            'kcal.min' => 'The kcal must be at least 0.',
            'prot.numeric' => 'The prot must be a number.',
            'prot.min' => 'The prot must be at least 0.',
            'carb.numeric' => 'The carb must be a number.',
            'carb.min' => 'The carb must be at least 0.',
            'fat.numeric' => 'The fat must be a number.',
            'fat.min' => 'The fat must be at least 0.',
        ];
    }

    public function toDTO(): UpdateIngredientDTO
    {
        $ingredient = $this->route('ingredient');
        $ingredientId = $ingredient instanceof Ingredient ? (string) $ingredient->id : (string) $ingredient;

        return new UpdateIngredientDTO(
            ingredientId: $ingredientId,
            name: $this->input('name'),
            amount: $this->input('amount') ? (string) $this->input('amount') : null,
            unit: $this->input('unit'),
            kcal: $this->input('kcal') ? (int) $this->input('kcal') : null,
            prot: $this->input('prot') ? (string) $this->input('prot') : null,
            carb: $this->input('carb') ? (string) $this->input('carb') : null,
            fat: $this->input('fat') ? (string) $this->input('fat') : null,
        );
    }
}

