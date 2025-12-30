<?php

declare(strict_types=1);

namespace App\Modules\MealLog\Presentation\Http\Requests;

use App\Modules\MealLog\Application\DTOs\MealLogFilterDTO;
use Illuminate\Foundation\Http\FormRequest;

class IndexMealLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'exists:users,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'type' => ['nullable', 'in:ingredient,recipe'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function toDTO(): MealLogFilterDTO
    {
        return new MealLogFilterDTO(
            userId: $this->input('user_id'),
            dateFrom: $this->input('date_from'),
            dateTo: $this->input('date_to'),
            type: $this->input('type'),
            page: $this->input('page') ? (int) $this->input('page') : null,
            perPage: $this->input('per_page') ? (int) $this->input('per_page') : null,
        );
    }
}

