<?php

declare(strict_types=1);

namespace App\Modules\MealLog\Presentation\Http\Controllers;

use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use App\Modules\MealLog\Application\Services\MealLogService;
use App\Modules\MealLog\Domain\Exceptions\InvalidMealLogException;
use App\Modules\MealLog\Domain\Exceptions\MealLogNotFoundException;
use App\Models\MealLog;
use App\Modules\MealLog\Presentation\Http\Requests\CreateMealLogRequest;
use App\Modules\MealLog\Presentation\Http\Requests\IndexMealLogRequest;
use App\Modules\MealLog\Presentation\Http\Requests\UpdateMealLogRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class MealLogController extends ApiController
{
    public function __construct(
        private readonly MealLogService $service
    ) {
    }

    public function index(IndexMealLogRequest $request): JsonResponse
    {
        $results = $this->service->findAll($request->toDTO());

        return $this->success($results);
    }

    public function show(MealLog $mealLog): JsonResponse
    {
        try {
            $mealLogData = $this->service->findById((string) $mealLog->id);
            return $this->success($mealLogData);
        } catch (MealLogNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        }
    }

    public function store(CreateMealLogRequest $request): JsonResponse
    {
        try {
            $mealLog = $this->service->create($request->toDTO());
            return $this->success($mealLog, 'Meal log created successfully', Response::HTTP_CREATED);
        } catch (InvalidMealLogException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to create meal log', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateMealLogRequest $request, MealLog $mealLog): JsonResponse
    {
        try {
            $updatedMealLog = $this->service->update($request->toDTO());
            return $this->success($updatedMealLog, 'Meal log updated successfully');
        } catch (MealLogNotFoundException|InvalidMealLogException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to update meal log', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(MealLog $mealLog): JsonResponse
    {
        try {
            $this->service->delete((string) $mealLog->id);
            return $this->success(null, 'Meal log deleted successfully', Response::HTTP_NO_CONTENT);
        } catch (MealLogNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to delete meal log', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

