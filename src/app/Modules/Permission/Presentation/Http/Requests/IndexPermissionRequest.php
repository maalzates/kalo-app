<?php

declare(strict_types=1);

namespace App\Modules\Permission\Presentation\Http\Requests;

use App\Modules\Permission\Application\DTOs\PermissionFilterDTO;
use Illuminate\Foundation\Http\FormRequest;

class IndexPermissionRequest extends FormRequest
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

    public function toDTO(): PermissionFilterDTO
    {
        return new PermissionFilterDTO(
            search: $this->input('search'),
            page: $this->input('page') ? (int) $this->input('page') : null,
            perPage: $this->input('per_page') ? (int) $this->input('per_page') : null,
        );
    }
}

