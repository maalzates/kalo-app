<?php

declare(strict_types=1);

namespace App\Modules\Biometric\Presentation\Http\Requests;

use App\Modules\Biometric\Application\DTOs\BiometricFilterDTO;
use Illuminate\Foundation\Http\FormRequest;

class IndexBiometricRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function toDTO(): BiometricFilterDTO
    {
        return new BiometricFilterDTO(
            userId: (string) auth()->id(),
            dateFrom: $this->input('date_from'),
            dateTo: $this->input('date_to'),
            page: $this->input('page') ? (int) $this->input('page') : null,
            perPage: $this->input('per_page') ? (int) $this->input('per_page') : null,
        );
    }
}

