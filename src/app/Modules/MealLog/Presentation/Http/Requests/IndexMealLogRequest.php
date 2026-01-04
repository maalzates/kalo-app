<?php

declare(strict_types=1);

namespace App\Modules\MealLog\Presentation\Http\Requests;

use App\Modules\MealLog\Application\DTOs\MealLogFilterDTO;
use Illuminate\Foundation\Http\FormRequest;

class IndexMealLogRequest extends FormRequest
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
            'type' => ['nullable', 'in:ingredient,recipe'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function toDTO(): MealLogFilterDTO
    {
        $page = $this->input('page');
        $page = $page !== null ? (int) $page : null;

        $perPage = $this->input('per_page');
        $perPage = $perPage !== null ? (int) $perPage : null;

        return new MealLogFilterDTO(
            userId: (string) auth()->id(),
            dateFrom: $this->input('date_from'),
            dateTo: $this->input('date_to'),
            type: $this->input('type'),
            page: $page,
            perPage: $perPage,
        );
    }
}

