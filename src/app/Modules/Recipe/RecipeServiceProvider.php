<?php

declare(strict_types=1);

namespace App\Modules\Recipe;

use App\Modules\Recipe\Domain\Contracts\RecipeRepositoryInterface;
use App\Modules\Recipe\Infrastructure\Repositories\RecipeRepository;
use Illuminate\Support\ServiceProvider;

class RecipeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            RecipeRepositoryInterface::class,
            RecipeRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}

