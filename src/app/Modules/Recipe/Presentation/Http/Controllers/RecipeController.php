<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Presentation\Http\Controllers;

use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use App\Modules\Recipe\Application\Services\RecipeService;
use App\Modules\Recipe\Domain\Exceptions\RecipeNotFoundException;
use App\Models\Recipe;
use App\Modules\Recipe\Presentation\Http\Requests\CreateRecipeRequest;
use App\Modules\Recipe\Presentation\Http\Requests\IndexRecipeRequest;
use App\Modules\Recipe\Presentation\Http\Requests\UpdateRecipeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class RecipeController extends ApiController
{
    public function __construct(
        private readonly RecipeService $service
    ) {
    }

    public function index(IndexRecipeRequest $request): JsonResponse
    {
        $results = $this->service->findAll($request->toDTO());

        return $this->success($results);
    }

    public function show(Recipe $recipe): JsonResponse
    {
        try {
            $recipeData = $this->service->findById((string) $recipe->id);
            return $this->success($recipeData);
        } catch (RecipeNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        }
    }

    public function store(CreateRecipeRequest $request): JsonResponse
    {
        try {
            $recipe = $this->service->create($request->toDTO());
            return $this->success($recipe, 'Recipe created successfully', Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return $this->error('Failed to create recipe', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateRecipeRequest $request, Recipe $recipe): JsonResponse
    {
        try {
            $updatedRecipe = $this->service->update($request->toDTO());
            return $this->success($updatedRecipe, 'Recipe updated successfully');
        } catch (RecipeNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to update recipe', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Recipe $recipe): JsonResponse
    {
        try {
            $this->service->delete((string) $recipe->id);
            return $this->success(null, 'Recipe deleted successfully', Response::HTTP_NO_CONTENT);
        } catch (RecipeNotFoundException $e) {
            return $this->error($e->getMessage(), $e->getHttpStatusCode());
        } catch (Throwable $e) {
            return $this->error('Failed to delete recipe', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

