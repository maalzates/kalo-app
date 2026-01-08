<?php

namespace App\Providers;

use App\Modules\Auth\Domain\Contracts\GoogleClientInterface;
use App\Modules\Auth\Infrastructure\Clients\GoogleClientRepository;
use Google_Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Google_Client as a singleton
        $this->app->singleton(Google_Client::class, function (Application $app) {
            $client = new Google_Client();
            $client->setClientId(config('services.google.client_id'));
            $client->setClientSecret(config('services.google.client_secret'));

            return $client;
        });

        // Register GoogleClientInterface binding
        $this->app->singleton(GoogleClientInterface::class, function (Application $app) {
            return new GoogleClientRepository(
                $app->make(Google_Client::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
