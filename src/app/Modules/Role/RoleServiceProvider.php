<?php

declare(strict_types=1);

namespace App\Modules\Role;

use App\Modules\Role\Domain\Contracts\RoleRepositoryInterface;
use App\Modules\Role\Infrastructure\Repositories\RoleRepository;
use Illuminate\Support\ServiceProvider;

class RoleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            RoleRepositoryInterface::class,
            RoleRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}

