<?php

declare(strict_types=1);

namespace App\Modules\Permission\Presentation\Http\Requests;

use App\Modules\Permission\Application\DTOs\CreatePermissionDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.unique' => 'The permission name has already been taken.',
        ];
    }

    public function toDTO(): CreatePermissionDTO
    {
        return new CreatePermissionDTO(
            name: $this->input('name'),
        );
    }
}

