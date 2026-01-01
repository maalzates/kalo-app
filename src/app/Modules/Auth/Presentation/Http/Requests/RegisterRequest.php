<?php

declare(strict_types=1);

namespace App\Modules\Auth\Presentation\Http\Requests;

use App\Modules\Auth\Application\DTOs\RegisterUserDTO;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female,other'],
            'height' => ['nullable', 'integer', 'min:100', 'max:250'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already registered.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters.',
            'birth_date.before' => 'Birth date must be in the past.',
            'height.min' => 'Height must be at least 100 cm.',
            'height.max' => 'Height cannot exceed 250 cm.',
        ];
    }

    public function toDTO(): RegisterUserDTO
    {
        return new RegisterUserDTO(
            name: $this->validated()['name'],
            email: $this->validated()['email'],
            password: $this->validated()['password'],
            phone: $this->validated()['phone'] ?? null,
            birthDate: $this->validated()['birth_date'] ?? null,
            gender: $this->validated()['gender'] ?? null,
            height: $this->validated()['height'] ?? null,
        );
    }
}

