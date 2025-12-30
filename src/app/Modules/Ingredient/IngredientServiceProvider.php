<?php

declare(strict_types=1);

namespace App\Modules\Ingredient;

use App\Modules\Ingredient\Domain\Contracts\IngredientRepositoryInterface;
use App\Modules\Ingredient\Infrastructure\Repositories\IngredientRepository;
use Illuminate\Support\ServiceProvider;

class IngredientServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            IngredientRepositoryInterface::class,
            IngredientRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}

