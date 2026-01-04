<?php

declare(strict_types=1);

namespace App\Modules\Ingredient\Presentation\Http\Requests;

use App\Modules\Ingredient\Application\DTOs\IngredientFilterDTO;
use Illuminate\Foundation\Http\FormRequest;

class IndexIngredientRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        // Normalize perPage (camelCase) to per_page (snake_case)
        if ($this->has('perPage') && !$this->has('per_page')) {
            $this->merge([
                'per_page' => $this->input('perPage'),
            ]);
        }
    }

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
            'include_public' => ['nullable', 'boolean'],
        ];
    }

    public function toDTO(): IngredientFilterDTO
    {
        $page = $this->input('page');
        $page = $page !== null ? (int) $page : null;

        $perPage = $this->input('per_page');
        $perPage = $perPage !== null ? (int) $perPage : null;

        return new IngredientFilterDTO(
            search: $this->input('search'),
            userId: (string) auth()->id(),
            unit: $this->input('unit'),
            page: $page,
            perPage: $perPage,
            includePublic: $this->boolean('include_public', false),
        );
    }
}

