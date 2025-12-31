<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\Http\Requests;

use App\Modules\User\Application\DTOs\UpdateUserDTO;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => $this->route('user')->id ?? $this->route('user'),
        ]);
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id ?? $this->route('user');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,' . $userId],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other'],
            'height' => ['nullable', 'integer', 'min:1', 'max:300'],
            'role_id' => ['nullable', 'integer', 'exists:roles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'gender.in' => 'The gender must be one of: male, female, other.',
            'height.integer' => 'The height must be an integer.',
            'height.min' => 'The height must be at least 1 cm.',
            'height.max' => 'The height must not exceed 300 cm.',
            'role_id.exists' => 'The selected role does not exist.',
        ];
    }

    public function toDTO(): UpdateUserDTO
    {
        $user = $this->route('user');
        $userId = $user instanceof User ? (string) $user->id : (string) $user;

        return new UpdateUserDTO(
            userId: $userId,
            name: $this->input('name'),
            email: $this->input('email'),
            phone: $this->input('phone'),
            birthDate: $this->input('birth_date'),
            gender: $this->input('gender'),
            height: $this->input('height') ? (int) $this->input('height') : null,
            roleId: $this->input('role_id') ? (string) $this->input('role_id') : null,
        );
    }
}

