<?php

declare(strict_types=1);

namespace App\Modules\Logs\Domain\Entities;

readonly class UsageLog
{
    public function __construct(
        public int $id,
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
        public ?string $errorMessage,
        public ?array $metadata,
        public \DateTimeInterface $createdAt,
        public \DateTimeInterface $updatedAt
    ) {}
}
