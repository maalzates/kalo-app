<?php

declare(strict_types=1);

namespace App\Modules\AI\Infrastructure\Repositories;

use App\Modules\AI\Domain\Contracts\GeminiRepositoryInterface;
use App\Modules\AI\Domain\Exceptions\GeminiException;
use App\Modules\AI\Infrastructure\Builders\PromptBuilder;
use App\Modules\AI\Infrastructure\Clients\GeminiClient;
use Throwable;

class GeminiRepository implements GeminiRepositoryInterface
{
    public function __construct(
        private readonly GeminiClient $client,
        private readonly PromptBuilder $promptBuilder
    ) {}

    public function analyzeFoodImage(string $imageBase64, string $mimeType, string $foodType = 'ingredient'): array
    {
        try {
            // Formatear el payload usando el builder
            $payload = $this->promptBuilder->formatForGemini($imageBase64, $mimeType, $foodType);

            // Enviar al cliente
            $response = $this->client->generateContent($payload);

            $textResponse = $response['candidates'][0]['content']['parts'][0]['text'] ?? '';

            // Limpieza de Markdown: Gemini a veces envuelve el JSON en bloques ```json ... ```
            $cleanJson = preg_replace('/^```json\s*|```$/m', '', $textResponse);

            return json_decode(trim($cleanJson), true) ?? throw new \Exception('Invalid JSON response from AI');

        } catch (Throwable $exception) {
            throw GeminiException::forFoodAnalysisCall($imageBase64, $mimeType, $exception);
        }
    }
}
