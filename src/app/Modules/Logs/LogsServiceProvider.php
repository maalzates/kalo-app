<?php

declare(strict_types=1);

namespace App\Modules\Logs;

use App\Modules\Logs\Domain\Contracts\UsageLogRepositoryInterface;
use App\Modules\Logs\Infrastructure\Repositories\UsageLogRepository;
use App\Modules\Logs\Infrastructure\Services\TokenExtractor;
use Illuminate\Support\ServiceProvider;

class LogsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UsageLogRepositoryInterface::class, UsageLogRepository::class);
        $this->app->singleton(TokenExtractor::class);
    }

    public function boot(): void
    {
        //
    }
}
