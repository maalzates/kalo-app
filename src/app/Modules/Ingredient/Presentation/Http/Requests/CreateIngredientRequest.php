<?php

declare(strict_types=1);

namespace App\Modules\Ingredient\Presentation\Http\Requests;

use App\Modules\Ingredient\Application\DTOs\CreateIngredientDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateIngredientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['required', 'in:g,ml,un'],
            'kcal' => ['required', 'integer', 'min:0'],
            'prot' => ['required', 'numeric', 'min:0'],
            'carb' => ['required', 'numeric', 'min:0'],
            'fat' => ['required', 'numeric', 'min:0'],
            'user_id' => ['nullable', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'amount.required' => 'The amount field is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least 0.01.',
            'unit.required' => 'The unit field is required.',
            'unit.in' => 'The unit must be one of: g, ml, un.',
            'kcal.required' => 'The kcal field is required.',
            'kcal.integer' => 'The kcal must be an integer.',
            'kcal.min' => 'The kcal must be at least 0.',
            'prot.required' => 'The prot field is required.',
            'prot.numeric' => 'The prot must be a number.',
            'prot.min' => 'The prot must be at least 0.',
            'carb.required' => 'The carb field is required.',
            'carb.numeric' => 'The carb must be a number.',
            'carb.min' => 'The carb must be at least 0.',
            'fat.required' => 'The fat field is required.',
            'fat.numeric' => 'The fat must be a number.',
            'fat.min' => 'The fat must be at least 0.',
            'user_id.exists' => 'The selected user does not exist.',
        ];
    }

    public function toDTO(): CreateIngredientDTO
    {
        return new CreateIngredientDTO(
            name: $this->input('name'),
            amount: (string) $this->input('amount'),
            unit: $this->input('unit'),
            kcal: (int) $this->input('kcal'),
            prot: (string) $this->input('prot'),
            carb: (string) $this->input('carb'),
            fat: (string) $this->input('fat'),
            userId: $this->input('user_id'),
        );
    }
}

