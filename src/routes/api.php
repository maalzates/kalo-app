<?php

declare(strict_types=1);

use App\Modules\AI\Presentation\Http\Controllers\FoodAnalysisController;
use App\Modules\Auth\Presentation\Http\Controllers\AuthController;
use App\Modules\Biometric\Presentation\Http\Controllers\BiometricController;
use App\Modules\Ingredient\Presentation\Http\Controllers\IngredientController;
use App\Modules\Macro\Presentation\Http\Controllers\MacroController;
use App\Modules\MealLog\Presentation\Http\Controllers\MealLogController;
use App\Modules\Permission\Presentation\Http\Controllers\PermissionController;
use App\Modules\Recipe\Presentation\Http\Controllers\RecipeController;
use App\Modules\Recipe\Presentation\Http\Controllers\RecipeIngredientController;
use App\Modules\Role\Presentation\Http\Controllers\RoleController;
use App\Modules\Role\Presentation\Http\Controllers\RolePermissionController;
use App\Modules\User\Presentation\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public routes (no authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

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
        Route::post('/ai', [MealLogController::class, 'storeFromAI']);
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

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::get('/{role}', [RoleController::class, 'show']);
        Route::put('/{role}', [RoleController::class, 'update']);
        Route::delete('/{role}', [RoleController::class, 'destroy']);

        Route::post('/{role}/permissions', [RolePermissionController::class, 'attach']);
        Route::delete('/{role}/permissions/{permission}', [RolePermissionController::class, 'detach']);
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::post('/', [PermissionController::class, 'store']);
        Route::get('/{permission}', [PermissionController::class, 'show']);
        Route::put('/{permission}', [PermissionController::class, 'update']);
        Route::delete('/{permission}', [PermissionController::class, 'destroy']);
    });

    Route::prefix('macros')->group(function () {
        Route::get('/', [MacroController::class, 'index']);
        Route::post('/', [MacroController::class, 'store']);
        Route::get('/{macro}', [MacroController::class, 'show']);
        Route::put('/{macro}', [MacroController::class, 'update']);
        Route::delete('/{macro}', [MacroController::class, 'destroy']);
    });

    Route::prefix('ai')->group(function () {
        Route::post('image/analyze', [FoodAnalysisController::class, 'store']);
    });
});
