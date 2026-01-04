<?php

declare(strict_types=1);

namespace App\Modules\AI\Domain\Contracts;

interface GeminiRepositoryInterface
{
    public function analyzeFoodImage(string $imageBase64, string $mimeType): array;
}
