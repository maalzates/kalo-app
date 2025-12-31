<?php

declare(strict_types=1);

namespace App\Modules\Role\Presentation\Http\Requests;

use App\Modules\Role\Application\DTOs\RoleFilterDTO;
use Illuminate\Foundation\Http\FormRequest;

class IndexRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function toDTO(): RoleFilterDTO
    {
        return new RoleFilterDTO(
            search: $this->input('search'),
            page: $this->input('page') ? (int) $this->input('page') : null,
            perPage: $this->input('per_page') ? (int) $this->input('per_page') : null,
        );
    }
}

