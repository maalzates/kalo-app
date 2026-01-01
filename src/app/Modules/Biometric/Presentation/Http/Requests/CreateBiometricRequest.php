<?php

declare(strict_types=1);

namespace App\Modules\Biometric\Presentation\Http\Requests;

use App\Modules\Biometric\Application\DTOs\CreateBiometricDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateBiometricRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'weight' => ['required', 'numeric', 'min:0.01', 'max:1000'],
            'fat_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'clean_mass' => ['nullable', 'numeric', 'min:0.01', 'max:1000'],
            'waist_circumference' => ['nullable', 'numeric', 'min:0.01', 'max:500'],
            'measured_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'weight.required' => 'The weight field is required.',
            'weight.numeric' => 'The weight must be a number.',
            'weight.min' => 'The weight must be at least 0.01 kg.',
            'weight.max' => 'The weight must not exceed 1000 kg.',
            'fat_percentage.numeric' => 'The fat_percentage must be a number.',
            'fat_percentage.min' => 'The fat_percentage must be at least 0.',
            'fat_percentage.max' => 'The fat_percentage must not exceed 100.',
            'clean_mass.numeric' => 'The clean_mass must be a number.',
            'clean_mass.min' => 'The clean_mass must be at least 0.01 kg.',
            'clean_mass.max' => 'The clean_mass must not exceed 1000 kg.',
            'waist_circumference.numeric' => 'The waist_circumference must be a number.',
            'waist_circumference.min' => 'The waist_circumference must be at least 0.01 cm.',
            'waist_circumference.max' => 'The waist_circumference must not exceed 500 cm.',
            'measured_at.date' => 'The measured_at must be a valid date.',
        ];
    }

    public function toDTO(): CreateBiometricDTO
    {
        return new CreateBiometricDTO(
            userId: (string) auth()->id(),
            weight: (string) $this->input('weight'),
            fatPercentage: $this->input('fat_percentage') ? (string) $this->input('fat_percentage') : null,
            cleanMass: $this->input('clean_mass') ? (string) $this->input('clean_mass') : null,
            waistCircumference: $this->input('waist_circumference') ? (string) $this->input('waist_circumference') : null,
            measuredAt: $this->input('measured_at'),
        );
    }
}

