<?php

declare(strict_types=1);

use App\Modules\Biometric\Presentation\Http\Controllers\BiometricController;
use App\Modules\Ingredient\Presentation\Http\Controllers\IngredientController;
use App\Modules\MealLog\Presentation\Http\Controllers\MealLogController;
use App\Modules\Recipe\Presentation\Http\Controllers\RecipeController;
use App\Modules\Recipe\Presentation\Http\Controllers\RecipeIngredientController;
use App\Modules\User\Presentation\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{user}', [UserController::class, 'show']);
    Route::put('/{user}', [UserController::class, 'update']);
    Route::delete('/{user}', [UserController::class, 'destroy']);
});

Route::prefix('ingredients')->group(function () {
    Route::get('/', [IngredientController::class, 'index']);
    Route::post('/', [IngredientController::class, 'store']);
    Route::get('/{ingredient}', [IngredientController::class, 'show']);
    Route::put('/{ingredient}', [IngredientController::class, 'update']);
    Route::delete('/{ingredient}', [IngredientController::class, 'destroy']);
});

Route::prefix('recipes')->group(function () {
    Route::get('/', [RecipeController::class, 'index']);
    Route::post('/', [RecipeController::class, 'store']);
    Route::get('/{recipe}', [RecipeController::class, 'show']);
    Route::put('/{recipe}', [RecipeController::class, 'update']);
    Route::delete('/{recipe}', [RecipeController::class, 'destroy']);

    Route::post('/{recipe}/ingredients', [RecipeIngredientController::class, 'attach']);
    Route::delete('/{recipe}/ingredients/{ingredient}', [RecipeIngredientController::class, 'detach']);
    Route::put('/{recipe}/ingredients/{ingredient}', [RecipeIngredientController::class, 'updatePivot']);
});

Route::prefix('meal-logs')->group(function () {
    Route::get('/', [MealLogController::class, 'index']);
    Route::post('/', [MealLogController::class, 'store']);
    Route::get('/{mealLog}', [MealLogController::class, 'show']);
    Route::put('/{mealLog}', [MealLogController::class, 'update']);
    Route::delete('/{mealLog}', [MealLogController::class, 'destroy']);
});

Route::prefix('biometrics')->group(function () {
    Route::get('/', [BiometricController::class, 'index']);
    Route::post('/', [BiometricController::class, 'store']);
    Route::get('/{biometric}', [BiometricController::class, 'show']);
    Route::put('/{biometric}', [BiometricController::class, 'update']);
    Route::delete('/{biometric}', [BiometricController::class, 'destroy']);
});

