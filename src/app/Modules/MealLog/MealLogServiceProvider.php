<?php

declare(strict_types=1);

namespace App\Modules\MealLog;

use App\Modules\MealLog\Domain\Contracts\MealLogRepositoryInterface;
use App\Modules\MealLog\Infrastructure\Repositories\MealLogRepository;
use Illuminate\Support\ServiceProvider;

class MealLogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            MealLogRepositoryInterface::class,
            MealLogRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}

