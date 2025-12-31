<?php

declare(strict_types=1);

namespace App\Modules\Permission\Presentation\Http\Requests;

use App\Modules\Permission\Application\DTOs\UpdatePermissionDTO;
use App\Modules\Permission\Infrastructure\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'permission_id' => $this->route('permission')->id ?? $this->route('permission'),
        ]);
    }

    public function rules(): array
    {
        $permissionId = $this->route('permission')->id ?? $this->route('permission');

        return [
            'name' => ['sometimes', 'string', 'max:255', 'unique:permissions,name,' . $permissionId],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'The permission name has already been taken.',
        ];
    }

    public function toDTO(): UpdatePermissionDTO
    {
        $permission = $this->route('permission');
        $permissionId = $permission instanceof Permission ? (string) $permission->id : (string) $permission;

        return new UpdatePermissionDTO(
            permissionId: $permissionId,
            name: $this->input('name'),
        );
    }
}

