<?php

declare(strict_types=1);

namespace App\Modules\AI\Application\Services;

use App\Modules\AI\Application\DTO\AnalyzeFoodImageDTO;
use App\Modules\AI\Domain\Contracts\GeminiRepositoryInterface;

class FoodAnalysisService
{
    public function __construct(
        private readonly GeminiRepositoryInterface $repository,
        private readonly ImageScalingService $imageScalingService
    ) {}

    public function analyzeFoodImage(AnalyzeFoodImageDTO $dto): array
    {
        $imageBase64 = $this->imageScalingService->scaleAndConvertToBase64($dto->image);

        return $this->repository->analyzeFoodImage($imageBase64, 'image/jpeg');
    }
}
