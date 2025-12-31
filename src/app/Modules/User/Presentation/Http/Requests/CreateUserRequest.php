<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\Http\Requests;

use App\Modules\User\Application\DTOs\CreateUserDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other'],
            'height' => ['nullable', 'integer', 'min:1', 'max:300'],
            'google_id' => ['nullable', 'string', 'max:255', 'unique:users,google_id'],
            'auth_provider' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'min:8'],
            'role_id' => ['nullable', 'integer', 'exists:roles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'gender.in' => 'The gender must be one of: male, female, other.',
            'height.integer' => 'The height must be an integer.',
            'height.min' => 'The height must be at least 1 cm.',
            'height.max' => 'The height must not exceed 300 cm.',
            'google_id.unique' => 'The Google ID has already been taken.',
            'password.min' => 'The password must be at least 8 characters.',
            'role_id.exists' => 'The selected role does not exist.',
        ];
    }

    public function toDTO(): CreateUserDTO
    {
        return new CreateUserDTO(
            name: $this->input('name'),
            email: $this->input('email'),
            phone: $this->input('phone'),
            birthDate: $this->input('birth_date'),
            gender: $this->input('gender'),
            height: $this->input('height') ? (int) $this->input('height') : null,
            googleId: $this->input('google_id'),
            authProvider: $this->input('auth_provider'),
            password: $this->input('password'),
            roleId: $this->input('role_id') ? (string) $this->input('role_id') : null,
        );
    }
}

