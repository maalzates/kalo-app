<?php

declare(strict_types=1);

namespace App\Modules\Role\Presentation\Http\Requests;

use App\Modules\Role\Application\DTOs\UpdateRoleDTO;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'role_id' => $this->route('role')->id ?? $this->route('role'),
        ]);
    }

    public function rules(): array
    {
        $roleId = $this->route('role')->id ?? $this->route('role');

        return [
            'name' => ['sometimes', 'string', 'max:255', 'unique:roles,name,' . $roleId],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'The role name has already been taken.',
        ];
    }

    public function toDTO(): UpdateRoleDTO
    {
        $role = $this->route('role');
        $roleId = $role instanceof Role ? (string) $role->id : (string) $role;

        return new UpdateRoleDTO(
            roleId: $roleId,
            name: $this->input('name'),
        );
    }
}

