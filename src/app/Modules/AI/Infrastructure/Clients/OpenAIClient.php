<?php

declare(strict_types=1);

namespace App\Modules\AI\Infrastructure\Clients;

use App\Modules\Core\Infrastructure\Clients\ApiClientAbstract;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class OpenAIClient extends ApiClientAbstract
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
        $apiKey = config('services.openai.api_key');
        $baseUrl = config('services.openai.api_url');

        $uri = sprintf('%s/chat/completions', $baseUrl);

        return $this->post($uri, [
            RequestOptions::JSON => $payload,
            RequestOptions::HEADERS => [
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ],
        ]);
    }
}
