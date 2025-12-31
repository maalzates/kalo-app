<?php

declare(strict_types=1);

namespace App\Modules\Macro\Presentation\Http\Requests;

use App\Modules\Macro\Application\DTOs\UpdateMacroDTO;
use App\Models\Macro;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMacroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'macro_id' => $this->route('macro')->id ?? $this->route('macro'),
        ]);
    }

    public function rules(): array
    {
        return [
            'kcal' => ['sometimes', 'integer', 'min:0'],
            'prot' => ['sometimes', 'numeric', 'min:0'],
            'carb' => ['sometimes', 'numeric', 'min:0'],
            'fat' => ['sometimes', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
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

    public function toDTO(): UpdateMacroDTO
    {
        $macro = $this->route('macro');
        $macroId = $macro instanceof Macro ? (string) $macro->id : (string) $macro;

        return new UpdateMacroDTO(
            macroId: $macroId,
            kcal: $this->input('kcal') ? (int) $this->input('kcal') : null,
            prot: $this->input('prot') ? (string) $this->input('prot') : null,
            carb: $this->input('carb') ? (string) $this->input('carb') : null,
            fat: $this->input('fat') ? (string) $this->input('fat') : null,
        );
    }
}

