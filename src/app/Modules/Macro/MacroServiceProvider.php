<?php

declare(strict_types=1);

namespace App\Modules\Macro;

use App\Modules\Macro\Domain\Contracts\MacroRepositoryInterface;
use App\Modules\Macro\Infrastructure\Repositories\MacroRepository;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            MacroRepositoryInterface::class,
            MacroRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}

