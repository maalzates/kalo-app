<?php

declare(strict_types=1);

namespace App\Modules\Auth;

use Illuminate\Support\ServiceProvider;
use App\Modules\Auth\Domain\Contracts\AuthRepositoryInterface;
use App\Modules\Auth\Infrastructure\Repositories\AuthRepository;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            AuthRepositoryInterface::class,
            AuthRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}

