<?php

declare(strict_types=1);

namespace App\Modules\AI;

use App\Modules\AI\Domain\Contracts\GeminiRepositoryInterface;
use App\Modules\AI\Infrastructure\Clients\GeminiClient;
use App\Modules\AI\Infrastructure\Repositories\GeminiRepository;
use App\Modules\Core\Infrastructure\Clients\GuzzleClientFactory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GeminiClient::class, function (Application $app) {
            return new GeminiClient(
                ($app->make(GuzzleClientFactory::class))->create([
                    'base_uri' => config('services.gemini.api_url'),
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'x-goog-api-key' => config('services.gemini.api_key'),
                    ],
                ])
            );
        });

        $this->app->bind(GeminiRepositoryInterface::class, GeminiRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
