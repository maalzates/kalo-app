<?php

declare(strict_types=1);

namespace App\Modules\AI\Infrastructure\Clients;

use App\Modules\Core\Infrastructure\Clients\ApiClientAbstract;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class OpenRouterClient extends ApiClientAbstract
{
    public function __construct(Client $client)
    {
        parent::__construct($client);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function chatCompletions(array $payload): array
    {
        $uri = config('services.openrouter.api_url');

        return $this->post($uri, [
            RequestOptions::JSON => $payload,
        ]);
    }
}
