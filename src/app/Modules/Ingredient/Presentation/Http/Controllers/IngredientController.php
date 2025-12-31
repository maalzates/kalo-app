<?php

declare(strict_types=1);

namespace App\Modules\Ingredient\Presentation\Http\Controllers;

use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use App\Modules\Ingredient\Application\Services\IngredientService;
use App\Modules\Ingredient\Domain\Exceptions\IngredientNotFoundException;
use App\Modules\Ingredient\Infrastructure\Models\Ingredient;
use App\Modules\Ingredient\Presentation\Http\Requests\CreateIngredientRequest;
use App\Modules\Ingredient\Presentation\Http\Requests\IndexIngredientRequest;
use App\Modules\Ingredient\Presentation\Http\Requests\UpdateIngredientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class IngredientController extends ApiController
{
    public function __construct(
        private readonly IngredientService $service
    ) {
    }

    public function index(IndexIngredientRequest $request): JsonResponse
    {
        $results = $this->service->findAll($request->toDTO());

        return $this->success($results);
    }

    public function show(Ingredient $ingredient): JsonResponse
    {
        try {
            $ingredientData = $this->service->findById((string) $ingredient->id);
            return $this->success($ingredientData);
        } catch (IngredientNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        }
    }

    public function store(CreateIngredientRequest $request): JsonResponse
    {
        try {
            $ingredient = $this->service->create($request->toDTO());
            return $this->success($ingredient, 'Ingredient created successfully', Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return $this->error('Failed to create ingredient', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateIngredientRequest $request, Ingredient $ingredient): JsonResponse
    {
        try {
            $updatedIngredient = $this->service->update($request->toDTO());
            return $this->success($updatedIngredient, 'Ingredient updated successfully');
        } catch (IngredientNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to update ingredient', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Ingredient $ingredient): JsonResponse
    {
        try {
            $this->service->delete((string) $ingredient->id);
            return $this->success(null, 'Ingredient deleted successfully', Response::HTTP_NO_CONTENT);
        } catch (IngredientNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to delete ingredient', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

