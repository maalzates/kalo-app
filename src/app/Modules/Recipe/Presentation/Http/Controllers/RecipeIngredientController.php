<?php

declare(strict_types=1);

namespace App\Modules\Recipe\Presentation\Http\Controllers;

use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use App\Models\Ingredient;
use App\Modules\Recipe\Application\Services\RecipeService;
use App\Models\Recipe;
use App\Modules\Recipe\Presentation\Http\Requests\AttachIngredientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RecipeIngredientController extends ApiController
{
    public function __construct(
        private readonly RecipeService $service
    ) {
    }

    public function attach(AttachIngredientRequest $request, Recipe $recipe): JsonResponse
    {
        return $this->success($this->service->attachIngredient($request->toDTO(), auth()->id()), 'Ingredient attached successfully', Response::HTTP_CREATED);
    }

    public function detach(Recipe $recipe, Ingredient $ingredient): JsonResponse
    {
        return $this->success($this->service->detachIngredient((string) $recipe->id, (string) $ingredient->id, auth()->id()), 'Ingredient detached successfully', Response::HTTP_NO_CONTENT);
    }

    public function updatePivot(AttachIngredientRequest $request, Recipe $recipe, Ingredient $ingredient): JsonResponse
    {
        return $this->success($this->service->updatePivot($request->toDTO(), auth()->id()), 'Ingredient pivot updated successfully');
    }
}

