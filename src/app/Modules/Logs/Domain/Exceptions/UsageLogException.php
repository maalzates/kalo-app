<?php

declare(strict_types=1);

namespace App\Modules\Logs\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use App\Modules\Logs\Application\DTO\CreateUsageLogDTO;
use Throwable;

class UsageLogException extends ApiException
{
    private const string MESSAGE = 'Failed to store usage log';

    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? self::MESSAGE);
    }

    public static function forFailedToStoreLog(CreateUsageLogDTO $dto, Throwable $exception): self
    {
        $usageException = new self('Failed to store usage log');
        $usageException->context = [
            'dto' => [
                'user_id' => $dto->userId,
                'ai_provider' => $dto->aiProvider,
                'action_type' => $dto->actionType,
                'food_type' => $dto->foodType,
                'image_size_bytes' => $dto->imageSizeBytes,
                'status' => $dto->status,
            ],
            'original_exception' => [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ],
        ];

        return $usageException;
    }
}
