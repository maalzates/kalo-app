<?php

declare(strict_types=1);

namespace App\Modules\Role\Presentation\Http\Requests;

use App\Modules\Role\Application\DTOs\AttachPermissionDTO;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class AttachPermissionRequest extends FormRequest
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
        return [
            'permission_id' => ['required', 'integer', 'exists:permissions,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'permission_id.required' => 'The permission_id field is required.',
            'permission_id.exists' => 'The selected permission does not exist.',
        ];
    }

    public function toDTO(): AttachPermissionDTO
    {
        $role = $this->route('role');
        $roleId = $role instanceof Role ? (string) $role->id : (string) $role;

        $permissionId = $this->input('permission_id');

        return new AttachPermissionDTO(
            roleId: $roleId,
            permissionId: (string) $permissionId,
        );
    }
}

