<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Presentation\Http\Controllers;

use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use App\Modules\Recipe\Application\Services\RecipeService;
use App\Models\Recipe;
use App\Modules\Recipe\Presentation\Http\Requests\CreateRecipeRequest;
use App\Modules\Recipe\Presentation\Http\Requests\IndexRecipeRequest;
use App\Modules\Recipe\Presentation\Http\Requests\UpdateRecipeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RecipeController extends ApiController
{
    public function __construct(
        private readonly RecipeService $service
    ) {
    }

    public function index(IndexRecipeRequest $request): JsonResponse
    {
        return $this->success($this->service->findAll($request->toDTO()));
    }

    public function show(Recipe $recipe): JsonResponse
    {
        if ($recipe->user_id !== auth()->id()) {
            return $this->error('Recipe not found', Response::HTTP_NOT_FOUND);
        }
        return $this->success($this->service->findById((string) $recipe->id, auth()->id()));
    }

    public function store(CreateRecipeRequest $request): JsonResponse
    {
        return $this->success($this->service->create($request->toDTO()), 'Recipe created successfully', Response::HTTP_CREATED);
    }

    public function update(UpdateRecipeRequest $request, Recipe $recipe): JsonResponse
    {
        if ($recipe->user_id !== auth()->id()) {
            return $this->error('Recipe not found', Response::HTTP_NOT_FOUND);
        }
        return $this->success($this->service->update($request->toDTO(), auth()->id()), 'Recipe updated successfully');
    }

    public function destroy(Recipe $recipe): JsonResponse
    {
        if ($recipe->user_id !== auth()->id()) {
            return $this->error('Recipe not found', Response::HTTP_NOT_FOUND);
        }
        return $this->success($this->service->delete((string) $recipe->id, auth()->id()), 'Recipe deleted successfully', Response::HTTP_NO_CONTENT);
    }
}

