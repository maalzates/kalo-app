<?php

declare(strict_types=1);

namespace App\Modules\AI\Domain\Contracts;

interface OpenAIRepositoryInterface
{
    /**
     * @param  string  $imageBase64
     * @param  string  $mimeType
     * @param  string  $foodType  'ingredient' | 'recipe'
     * @return array<string, mixed>
     */
    public function analyzeFoodImage(string $imageBase64, string $mimeType, string $foodType = 'ingredient'): array;
}
