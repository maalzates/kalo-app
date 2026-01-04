<?php

declare(strict_types=1);

namespace App\Modules\AI\Application\DTO;

use Illuminate\Http\UploadedFile;

readonly class AnalyzeFoodImageDTO
{
    public function __construct(
        public string $userId,
        public UploadedFile $image,
        public string $mimeType
    ) {}
}
