<?php

declare(strict_types=1);

namespace App\Modules\Biometric;

use App\Modules\Biometric\Domain\Contracts\BiometricRepositoryInterface;
use App\Modules\Biometric\Infrastructure\Repositories\BiometricRepository;
use Illuminate\Support\ServiceProvider;

class BiometricServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            BiometricRepositoryInterface::class,
            BiometricRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}

