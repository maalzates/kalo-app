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
    // Usamos rtrim para asegurar que NO haya barra al final de la URL base
    $baseUrl = rtrim(config('services.gemini.api_url'), '/');

    // Construcción limpia: nos aseguramos de que solo haya una barra entre el base y models
    $uri = "{$baseUrl}/models/{$model}:generateContent?key={$apiKey}";

    // Debug opcional: Descomenta la siguiente línea una vez para ver la URL en el log
    // \Log::info("Gemini Request URI: " . $uri);

    return $this->post($uri, [
        RequestOptions::JSON => $payload,
    ]);
    }
}
