<?php

declare(strict_types=1);

namespace App\Modules\AI\Infrastructure\Clients;

use App\Modules\Core\Infrastructure\Clients\ApiClientAbstract;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class GeminiClient extends ApiClientAbstract
{
    public function __construct(Client $client)
    {
        parent::__construct($client);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function generateContent(array $payload): array
    {
        $model = config('services.gemini.model');
        $apiKey = config('services.gemini.api_key');
        $baseUrl = config('services.gemini.api_url');

        $uri = sprintf('%smodels/%s:generateContent?key=%s', $baseUrl, $model, $apiKey);

        return $this->post($uri, [
            RequestOptions::JSON => $payload,
        ]);
    }
}
