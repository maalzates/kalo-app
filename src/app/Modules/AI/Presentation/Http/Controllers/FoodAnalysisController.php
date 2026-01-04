<?php

declare(strict_types=1);

namespace App\Modules\AI\Presentation\Http\Controllers;

use App\Modules\AI\Application\Services\FoodAnalysisService;
use App\Modules\AI\Presentation\Http\Requests\AnalyzeFoodImageRequest;
use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class FoodAnalysisController extends ApiController
{
    public function __construct(
        private readonly FoodAnalysisService $service
    ) {}

    public function analyze(AnalyzeFoodImageRequest $request): JsonResponse
    {
        return $this->success($this->service->analyzeFoodImage($request->toDTO()));
    }
}
