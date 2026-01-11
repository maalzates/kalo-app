<?php

declare(strict_types=1);

namespace App\Modules\AI;

use App\Modules\AI\Domain\Contracts\GeminiRepositoryInterface;
use App\Modules\AI\Domain\Contracts\OpenRouterRepositoryInterface;
use App\Modules\AI\Infrastructure\Builders\PromptBuilder;
use App\Modules\AI\Infrastructure\Clients\GeminiClient;
use App\Modules\AI\Infrastructure\Clients\OpenRouterClient;
use App\Modules\AI\Infrastructure\Repositories\GeminiRepository;
use App\Modules\AI\Infrastructure\Repositories\OpenRouterRepository;
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

        $this->app->singleton(PromptBuilder::class);

        $this->app->bind(GeminiRepositoryInterface::class, GeminiRepository::class);

        $this->app->singleton(OpenRouterClient::class, function (Application $app) {
            return new OpenRouterClient(
                ($app->make(GuzzleClientFactory::class))->create([
                    'base_uri' => config('services.openrouter.api_url'),
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer '.config('services.openrouter.api_key'),
                        'HTTP-Referer' => config('services.openrouter.http_referer'),
                        'X-Title' => config('services.openrouter.app_name'),
                    ],
                ])
            );
        });

        $this->app->bind(OpenRouterRepositoryInterface::class, OpenRouterRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
