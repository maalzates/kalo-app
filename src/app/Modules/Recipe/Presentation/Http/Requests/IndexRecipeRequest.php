<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Presentation\Http\Requests;

use App\Modules\Recipe\Application\DTOs\RecipeFilterDTO;
use Illuminate\Foundation\Http\FormRequest;

class IndexRecipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'user_id' => ['nullable', 'exists:users,id'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function toDTO(): RecipeFilterDTO
    {
        return new RecipeFilterDTO(
            search: $this->input('search'),
            userId: $this->input('user_id'),
            page: $this->input('page') ? (int) $this->input('page') : null,
            perPage: $this->input('per_page') ? (int) $this->input('per_page') : null,
        );
    }
}

