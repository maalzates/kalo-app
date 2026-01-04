<?php

declare(strict_types=1);

namespace App\Modules\MealLog\Presentation\Http\Controllers;

use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use App\Modules\MealLog\Application\Services\MealLogService;
use App\Models\MealLog;
use App\Modules\MealLog\Presentation\Http\Requests\CreateMealLogFromAIRequest;
use App\Modules\MealLog\Presentation\Http\Requests\CreateMealLogRequest;
use App\Modules\MealLog\Presentation\Http\Requests\IndexMealLogRequest;
use App\Modules\MealLog\Presentation\Http\Requests\UpdateMealLogRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class MealLogController extends ApiController
{
    public function __construct(
        private readonly MealLogService $service
    ) {
    }

    public function index(IndexMealLogRequest $request): JsonResponse
    {
        return $this->success($this->service->findAll($request->toDTO()));
    }

    public function show(MealLog $mealLog): JsonResponse
    {
        if ($mealLog->user_id !== auth()->id()) {
            return $this->error('Meal log not found', Response::HTTP_NOT_FOUND);
        }
        return $this->success($this->service->findById((string) $mealLog->id, auth()->id()));
    }

    public function store(CreateMealLogRequest $request): JsonResponse
    {
        return $this->success($this->service->create($request->toDTO()), 'Meal log created successfully', Response::HTTP_CREATED);
    }

    public function storeFromAI(CreateMealLogFromAIRequest $request): JsonResponse
    {
        return $this->success($this->service->createFromAI($request->toDTO()), 'Meal log created successfully from AI', Response::HTTP_CREATED);
    }

    public function update(UpdateMealLogRequest $request, MealLog $mealLog): JsonResponse
    {
        if ($mealLog->user_id !== auth()->id()) {
            return $this->error('Meal log not found', Response::HTTP_NOT_FOUND);
        }
        return $this->success($this->service->update($request->toDTO(), auth()->id()), 'Meal log updated successfully');
    }

    public function destroy(MealLog $mealLog): JsonResponse
    {
        if ($mealLog->user_id !== auth()->id()) {
            return $this->error('Meal log not found', Response::HTTP_NOT_FOUND);
        }
        return $this->success($this->service->delete((string) $mealLog->id, auth()->id()), 'Meal log deleted successfully', Response::HTTP_NO_CONTENT);
    }
}

