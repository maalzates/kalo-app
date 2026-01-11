<?php

declare(strict_types=1);

namespace App\Modules\Logs\Application\DTO;

readonly class CreateUsageLogDTO
{
    public function __construct(
        public int $userId,
        public string $aiProvider,
        public string $actionType,
        public ?string $foodType,
        public int $imageSizeBytes,
        public ?int $inputTokens,
        public ?int $outputTokens,
        public ?int $totalTokens,
        public ?float $costUsd,
        public ?int $requestDurationMs,
        public string $status,
        public ?string $errorMessage = null,
        public ?array $metadata = null
    ) {}
}
