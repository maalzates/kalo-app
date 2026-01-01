<?php

declare(strict_types=1);

namespace App\Modules\Macro\Presentation\Http\Requests;

use App\Modules\Macro\Application\DTOs\MacroFilterDTO;
use Illuminate\Foundation\Http\FormRequest;

class IndexMacroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function toDTO(): MacroFilterDTO
    {
        return new MacroFilterDTO(
            userId: (string) auth()->id(),
            page: $this->input('page') ? (int) $this->input('page') : null,
            perPage: $this->input('per_page') ? (int) $this->input('per_page') : null,
        );
    }
}

