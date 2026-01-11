<?php

declare(strict_types=1);

namespace App\Modules\AI\Presentation\Http\Requests;

use App\Modules\AI\Application\DTO\AnalyzeFoodImageDTO;
use Illuminate\Foundation\Http\FormRequest;

class AnalyzeFoodImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
            'food_type' => ['sometimes', 'string', 'in:ingredient,recipe'],
        ];
    }

    public function toDTO(): AnalyzeFoodImageDTO
    {
        return new AnalyzeFoodImageDTO(
            image: $this->file('image'),
            mimeType: $this->file('image')->getMimeType(),
            foodType: $this->input('food_type', 'ingredient')
        );
    }
}
