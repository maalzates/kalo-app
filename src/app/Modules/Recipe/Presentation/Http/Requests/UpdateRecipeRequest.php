<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Presentation\Http\Requests;

use App\Modules\Recipe\Application\DTOs\UpdateRecipeDTO;
use App\Modules\Recipe\Infrastructure\Models\Recipe;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRecipeRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'servings' => ['sometimes', 'integer', 'min:1'],
            'total_kcal' => ['sometimes', 'integer', 'min:0'],
            'total_prot' => ['sometimes', 'numeric', 'min:0'],
            'total_carb' => ['sometimes', 'numeric', 'min:0'],
            'total_fat' => ['sometimes', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'servings.integer' => 'The servings must be an integer.',
            'servings.min' => 'The servings must be at least 1.',
            'total_kcal.integer' => 'The total_kcal must be an integer.',
            'total_kcal.min' => 'The total_kcal must be at least 0.',
            'total_prot.numeric' => 'The total_prot must be a number.',
            'total_prot.min' => 'The total_prot must be at least 0.',
            'total_carb.numeric' => 'The total_carb must be a number.',
            'total_carb.min' => 'The total_carb must be at least 0.',
            'total_fat.numeric' => 'The total_fat must be a number.',
            'total_fat.min' => 'The total_fat must be at least 0.',
        ];
    }

    public function toDTO(): UpdateRecipeDTO
    {
        $recipe = $this->route('recipe');
        $recipeId = $recipe instanceof Recipe ? (string) $recipe->id : (string) $recipe;

        return new UpdateRecipeDTO(
            recipeId: $recipeId,
            name: $this->input('name'),
            servings: $this->input('servings') ? (int) $this->input('servings') : null,
            totalKcal: $this->input('total_kcal') ? (int) $this->input('total_kcal') : null,
            totalProt: $this->input('total_prot') ? (string) $this->input('total_prot') : null,
            totalCarb: $this->input('total_carb') ? (string) $this->input('total_carb') : null,
            totalFat: $this->input('total_fat') ? (string) $this->input('total_fat') : null,
        );
    }
}

