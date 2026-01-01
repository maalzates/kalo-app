<?php

declare(strict_types=1);

namespace App\Modules\Ingredient\Presentation\Http\Requests;

use App\Modules\Ingredient\Application\DTOs\IngredientFilterDTO;
use Illuminate\Foundation\Http\FormRequest;

class IndexIngredientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'unit' => ['nullable', 'in:g,ml,un'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function toDTO(): IngredientFilterDTO
    {
        return new IngredientFilterDTO(
            search: $this->input('search'),
            userId: (string) auth()->id(),
            unit: $this->input('unit'),
            page: $this->input('page') ? (int) $this->input('page') : null,
            perPage: $this->input('per_page') ? (int) $this->input('per_page') : null,
        );
    }
}

