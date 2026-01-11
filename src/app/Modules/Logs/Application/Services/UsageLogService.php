<?php

declare(strict_types=1);

namespace App\Modules\Logs\Application\Services;

use App\Modules\Logs\Application\DTO\CreateUsageLogDTO;
use App\Modules\Logs\Domain\Contracts\UsageLogRepositoryInterface;
use App\Modules\Logs\Domain\Entities\UsageLog;

class UsageLogService
{
    public function __construct(
        private readonly UsageLogRepositoryInterface $repository
    ) {}

    public function log(
        int $userId,
        string $aiProvider,
        string $actionType,
        ?string $foodType,
        int $imageSizeBytes,
        ?int $inputTokens,
        ?int $outputTokens,
        ?int $totalTokens,
        ?float $costUsd,
        ?int $requestDurationMs,
        string $status,
        ?string $errorMessage = null,
        ?array $metadata = null
    ): UsageLog {
        $dto = new CreateUsageLogDTO(
            userId: $userId,
            aiProvider: $aiProvider,
            actionType: $actionType,
            foodType: $foodType,
            imageSizeBytes: $imageSizeBytes,
            inputTokens: $inputTokens,
            outputTokens: $outputTokens,
            totalTokens: $totalTokens,
            costUsd: $costUsd,
            requestDurationMs: $requestDurationMs,
            status: $status,
            errorMessage: $errorMessage,
            metadata: $metadata
        );

        return $this->repository->create($dto);
    }
}
