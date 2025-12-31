<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Presentation\Http\Controllers;

use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use App\Modules\Ingredient\Infrastructure\Models\Ingredient;
use App\Modules\Recipe\Application\Services\RecipeService;
use App\Modules\Recipe\Domain\Exceptions\RecipeNotFoundException;
use App\Modules\Recipe\Infrastructure\Models\Recipe;
use App\Modules\Recipe\Presentation\Http\Requests\AttachIngredientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class RecipeIngredientController extends ApiController
{
    public function __construct(
        private readonly RecipeService $service
    ) {
    }

    public function attach(AttachIngredientRequest $request, Recipe $recipe): JsonResponse
    {
        try {
            $this->service->attachIngredient($request->toDTO());
            return $this->success(null, 'Ingredient attached successfully', Response::HTTP_CREATED);
        } catch (RecipeNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to attach ingredient', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function detach(Recipe $recipe, Ingredient $ingredient): JsonResponse
    {
        try {
            $this->service->detachIngredient((string) $recipe->id, (string) $ingredient->id);
            return $this->success(null, 'Ingredient detached successfully', Response::HTTP_NO_CONTENT);
        } catch (RecipeNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to detach ingredient', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updatePivot(AttachIngredientRequest $request, Recipe $recipe, Ingredient $ingredient): JsonResponse
    {
        try {
            $dto = $request->toDTO();
            $this->service->updatePivot($dto);
            return $this->success(null, 'Ingredient pivot updated successfully');
        } catch (RecipeNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to update ingredient pivot', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

