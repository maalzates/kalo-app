<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\Http\Requests;

use App\Modules\User\Application\DTOs\UpdateWeightDTO;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWeightRequest extends FormRequest
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
        return [
            'weight' => ['required', 'numeric', 'min:0.1', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'weight.required' => 'El peso es requerido.',
            'weight.numeric' => 'El peso debe ser un número.',
            'weight.min' => 'El peso debe ser mayor a 0.',
            'weight.max' => 'El peso no puede exceder los 1000 kg.',
        ];
    }

    public function toDTO(): UpdateWeightDTO
    {
        $user = $this->route('user');
        $userId = $user instanceof User ? (string) $user->id : (string) $user;

        return new UpdateWeightDTO(
            userId: $userId,
            weight: (float) $this->input('weight'),
        );
    }
}
