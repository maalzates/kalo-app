<?php

declare(strict_types=1);

namespace App\Modules\Biometric\Presentation\Http\Requests;

use App\Modules\Biometric\Application\DTOs\BiometricFilterDTO;
use Illuminate\Foundation\Http\FormRequest;

class IndexBiometricRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        // Normalize perPage (camelCase) to per_page (snake_case)
        if ($this->has('perPage') && !$this->has('per_page')) {
            $this->merge([
                'per_page' => $this->input('perPage'),
            ]);
        }
    }

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
        $page = $this->input('page');
        $page = $page !== null ? (int) $page : null;

        $perPage = $this->input('per_page');
        $perPage = $perPage !== null ? (int) $perPage : null;

        return new BiometricFilterDTO(
            userId: (string) auth()->id(),
            dateFrom: $this->input('date_from'),
            dateTo: $this->input('date_to'),
            page: $page,
            perPage: $perPage,
        );
    }
}

