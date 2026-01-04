<?php

declare(strict_types=1);

namespace App\Modules\Role\Presentation\Http\Requests;

use App\Modules\Role\Application\DTOs\RoleFilterDTO;
use Illuminate\Foundation\Http\FormRequest;

class IndexRoleRequest extends FormRequest
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
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function toDTO(): RoleFilterDTO
    {
        $page = $this->input('page');
        $page = $page !== null ? (int) $page : null;

        $perPage = $this->input('per_page');
        $perPage = $perPage !== null ? (int) $perPage : null;

        return new RoleFilterDTO(
            search: $this->input('search'),
            page: $page,
            perPage: $perPage,
        );
    }
}

