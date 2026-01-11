<?php

declare(strict_types=1);

namespace App\Modules\Logs\Infrastructure\Repositories;

use App\Modules\Logs\Application\DTO\CreateUsageLogDTO;
use App\Modules\Logs\Domain\Contracts\UsageLogRepositoryInterface;
use App\Modules\Logs\Domain\Entities\UsageLog;
use App\Modules\Logs\Domain\Exceptions\UsageLogException;
use App\Modules\Logs\Infrastructure\Persistence\Eloquent\UsageLogModel;
use Throwable;

class UsageLogRepository implements UsageLogRepositoryInterface
{
    public function create(CreateUsageLogDTO $dto): UsageLog
    {
        try {
            $model = UsageLogModel::create([
                'user_id' => $dto->userId,
                'ai_provider' => $dto->aiProvider,
                'action_type' => $dto->actionType,
                'food_type' => $dto->foodType,
                'image_size_bytes' => $dto->imageSizeBytes,
                'input_tokens' => $dto->inputTokens,
                'output_tokens' => $dto->outputTokens,
                'total_tokens' => $dto->totalTokens,
                'cost_usd' => $dto->costUsd,
                'request_duration_ms' => $dto->requestDurationMs,
                'status' => $dto->status,
                'error_message' => $dto->errorMessage,
                'metadata' => $dto->metadata,
            ]);

            return new UsageLog(
                id: $model->id,
                userId: $model->user_id,
                aiProvider: $model->ai_provider,
                actionType: $model->action_type,
                foodType: $model->food_type,
                imageSizeBytes: $model->image_size_bytes,
                inputTokens: $model->input_tokens,
                outputTokens: $model->output_tokens,
                totalTokens: $model->total_tokens,
                costUsd: $model->cost_usd,
                requestDurationMs: $model->request_duration_ms,
                status: $model->status,
                errorMessage: $model->error_message,
                metadata: $model->metadata,
                createdAt: $model->created_at,
                updatedAt: $model->updated_at
            );
        } catch (Throwable $exception) {
            throw UsageLogException::forFailedToStoreLog($dto, $exception);
        }
    }
}
