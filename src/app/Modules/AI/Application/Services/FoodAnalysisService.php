<?php

declare(strict_types=1);

namespace App\Modules\AI\Application\Services;

use App\Modules\AI\Application\DTO\AnalyzeFoodImageDTO;
use App\Modules\AI\Domain\Contracts\GeminiRepositoryInterface;
use App\Modules\Logs\Application\Services\UsageLogService;
use App\Modules\Logs\Infrastructure\Services\TokenExtractor;

class FoodAnalysisService
{
    public function __construct(
        private readonly GeminiRepositoryInterface $repository,
        private readonly ImageScalingService $imageScalingService,
        private readonly UsageLogService $usageLogService,
        private readonly TokenExtractor $tokenExtractor
    ) {}

    public function analyzeFoodImage(AnalyzeFoodImageDTO $dto): array
    {
        $startTime = microtime(true);
        $imageBase64 = $this->imageScalingService->scaleAndConvertToBase64($dto->image);
        $imageSizeBytes = strlen($imageBase64);
        $provider = 'gemini'; // Por ahora hardcodeado, luego puede venir de config

        $response = $this->repository->analyzeFoodImage($imageBase64, 'image/jpeg', $dto->foodType);

        // Extraer tokens de la respuesta
        $tokens = $this->tokenExtractor->extract($provider, $response);
        $durationMs = (int) ((microtime(true) - $startTime) * 1000);

        // Loguear el uso exitoso
        $this->usageLogService->log(
            userId: $dto->userId,
            aiProvider: $provider,
            actionType: 'food_analysis',
            foodType: $dto->foodType,
            imageSizeBytes: $imageSizeBytes,
            inputTokens: $tokens['input_tokens'],
            outputTokens: $tokens['output_tokens'],
            totalTokens: $tokens['total_tokens'],
            costUsd: null, // Puede calcularse con base en tokens y tarifa del proveedor
            requestDurationMs: $durationMs,
            status: 'success'
        );

        // Devolver solo el contenido, sin los metadatos
        return $response['content'];
    }
}
