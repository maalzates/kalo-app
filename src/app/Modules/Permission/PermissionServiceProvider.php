<?php

declare(strict_types=1);

namespace App\Modules\Permission;

use App\Modules\Permission\Domain\Contracts\PermissionRepositoryInterface;
use App\Modules\Permission\Infrastructure\Repositories\PermissionRepository;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            PermissionRepositoryInterface::class,
            PermissionRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}

