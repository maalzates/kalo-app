<?php

declare(strict_types=1);

namespace App\Modules\Ingredient\Presentation\Http\Controllers;

use App\Modules\Core\Presentation\Http\Controllers\ApiController;
use App\Modules\Ingredient\Application\Services\IngredientService;
use App\Models\Ingredient;
use App\Modules\Ingredient\Presentation\Http\Requests\CreateIngredientRequest;
use App\Modules\Ingredient\Presentation\Http\Requests\IndexIngredientRequest;
use App\Modules\Ingredient\Presentation\Http\Requests\UpdateIngredientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class IngredientController extends ApiController
{
    public function __construct(
        private readonly IngredientService $service
    ) {
    }

    public function index(IndexIngredientRequest $request): JsonResponse
    {
        return $this->success($this->service->findAll($request->toDTO()));
    }

    public function show(Ingredient $ingredient): JsonResponse
    {
        if ($ingredient->user_id !== auth()->id()) {
            return $this->error('Ingredient not found', Response::HTTP_NOT_FOUND);
        }
        return $this->success($this->service->findById((string) $ingredient->id, auth()->id()));
    }

    public function store(CreateIngredientRequest $request): JsonResponse
    {
        return $this->success($this->service->create($request->toDTO()), 'Ingredient created successfully', Response::HTTP_CREATED);
    }

    public function update(UpdateIngredientRequest $request, Ingredient $ingredient): JsonResponse
    {
        if ($ingredient->user_id !== auth()->id()) {
            return $this->error('Ingredient not found', Response::HTTP_NOT_FOUND);
        }
        return $this->success($this->service->update($request->toDTO(), auth()->id()), 'Ingredient updated successfully');
    }

    public function destroy(Ingredient $ingredient): JsonResponse
    {
        if ($ingredient->user_id !== auth()->id()) {
            return $this->error('Ingredient not found', Response::HTTP_NOT_FOUND);
        }
        return $this->success($this->service->delete((string) $ingredient->id, auth()->id()), 'Ingredient deleted successfully', Response::HTTP_NO_CONTENT);
    }
}

