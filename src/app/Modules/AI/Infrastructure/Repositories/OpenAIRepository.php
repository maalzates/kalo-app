<?php

declare(strict_types=1);

namespace App\Modules\AI\Infrastructure\Repositories;

use App\Modules\AI\Domain\Contracts\OpenAIRepositoryInterface;
use App\Modules\AI\Domain\Exceptions\OpenAIException;
use App\Modules\AI\Infrastructure\Builders\PromptBuilder;
use App\Modules\AI\Infrastructure\Clients\OpenAIClient;
use Throwable;

class OpenAIRepository implements OpenAIRepositoryInterface
{
    public function __construct(
        private readonly OpenAIClient $client,
        private readonly PromptBuilder $promptBuilder
    ) {}

    public function analyzeFoodImage(string $imageBase64, string $mimeType, string $foodType = 'ingredient'): array
    {
        try {
            // Formatear el payload usando el builder
            $payload = $this->promptBuilder->formatForOpenAI($imageBase64, $mimeType, $foodType);

            // Enviar al cliente
            $response = $this->client->generateContent($payload);

            $content = $response['choices'][0]['message']['content'] ?? '';

            // Limpieza de Markdown: OpenAI a veces envuelve el JSON en bloques ```json ... ```
            $cleanJson = preg_replace('/^```json\\s*|```$/m', '', $content);

            return json_decode(trim($cleanJson), true) ?? throw new \Exception('Invalid JSON response from AI');

        } catch (Throwable $exception) {
            throw OpenAIException::forFoodAnalysisCall($imageBase64, $mimeType, $exception);
        }
    }
}
