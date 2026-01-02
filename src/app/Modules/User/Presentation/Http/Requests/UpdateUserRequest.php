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
            'weight' => ['nullable', 'numeric', 'min:0', 'max:1000'],
            'role_id' => ['nullable', 'integer', 'exists:roles,id'],
            'country_code' => ['nullable', 'string', 'max:10'],
            'activity_level' => ['nullable', 'string', 'in:Sedentario,Ligero,Moderado,Muy Activo'],
            'goal_type' => ['nullable', 'in:cut,maintain,grow'],
            'macro_calculation_mode' => ['nullable', 'string', 'max:255'],
            'current_password' => ['required_with:new_password', 'string'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'new_password_confirmation' => ['nullable', 'string'],
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
            weight: $this->input('weight') ? (float) $this->input('weight') : null,
            roleId: $this->input('role_id') ? (string) $this->input('role_id') : null,
            countryCode: $this->input('country_code'),
            activityLevel: $this->input('activity_level'),
            goalType: $this->input('goal_type'),
            macroCalculationMode: $this->input('macro_calculation_mode'),
            currentPassword: $this->input('current_password'),
            newPassword: $this->input('new_password'),
        );
    }
}

