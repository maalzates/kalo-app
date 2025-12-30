<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\Http\Requests;

use App\Modules\User\Application\DTOs\UserFilterDTO;
use Illuminate\Foundation\Http\FormRequest;

class IndexUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female,other'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function toDTO(): UserFilterDTO
    {
        return new UserFilterDTO(
            search: $this->input('search'),
            gender: $this->input('gender'),
            page: $this->input('page') ? (int) $this->input('page') : null,
            perPage: $this->input('per_page') ? (int) $this->input('per_page') : null,
        );
    }
}

