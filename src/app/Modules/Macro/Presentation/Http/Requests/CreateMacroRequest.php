<?php

declare(strict_types=1);

namespace App\Modules\Macro\Presentation\Http\Requests;

use App\Modules\Macro\Application\DTOs\CreateMacroDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateMacroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kcal' => ['required', 'integer', 'min:0'],
            'prot' => ['required', 'numeric', 'min:0'],
            'carb' => ['required', 'numeric', 'min:0'],
            'fat' => ['required', 'numeric', 'min:0'],
            'user_id' => ['required', 'integer', 'exists:users,id', 'unique:macros,user_id'],
        ];
    }

    public function messages(): array
    {
        return [
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
            'user_id.required' => 'The user_id field is required.',
            'user_id.exists' => 'The selected user does not exist.',
            'user_id.unique' => 'This user already has macro goals defined.',
        ];
    }

    public function toDTO(): CreateMacroDTO
    {
        return new CreateMacroDTO(
            kcal: (int) $this->input('kcal'),
            prot: (string) $this->input('prot'),
            carb: (string) $this->input('carb'),
            fat: (string) $this->input('fat'),
            userId: (string) $this->input('user_id'),
        );
    }
}

