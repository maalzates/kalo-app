<?php

declare(strict_types=1);

namespace App\Modules\Role\Presentation\Http\Requests;

use App\Modules\Role\Application\DTOs\CreateRoleDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.unique' => 'The role name has already been taken.',
            'permission_ids.array' => 'The permission_ids must be an array.',
            'permission_ids.*.integer' => 'Each permission ID must be an integer.',
            'permission_ids.*.exists' => 'One or more selected permissions do not exist.',
        ];
    }

    public function toDTO(): CreateRoleDTO
    {
        return new CreateRoleDTO(
            name: $this->input('name'),
            permissionIds: $this->input('permission_ids'),
        );
    }
}

