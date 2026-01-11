<?php

declare(strict_types=1);

namespace App\Modules\Logs\Infrastructure\Services;

class TokenExtractor
{
    /**
     * Extrae tokens de una respuesta de Gemini
     *
     * Estructura esperada:
     * {
     *   "usageMetadata": {
     *     "promptTokenCount": 123,
     *     "candidatesTokenCount": 456,
     *     "totalTokenCount": 579
     *   }
     * }
     */
    public function extractFromGemini(array $response): array
    {
        $usageMetadata = $response['usageMetadata'] ?? [];

        return [
            'input_tokens' => $usageMetadata['promptTokenCount'] ?? null,
            'output_tokens' => $usageMetadata['candidatesTokenCount'] ?? null,
            'total_tokens' => $usageMetadata['totalTokenCount'] ?? null,
        ];
    }

    /**
     * Extrae tokens de una respuesta de OpenAI
     *
     * Estructura esperada:
     * {
     *   "usage": {
     *     "prompt_tokens": 123,
     *     "completion_tokens": 456,
     *     "total_tokens": 579
     *   }
     * }
     */
    public function extractFromOpenAI(array $response): array
    {
        $usage = $response['usage'] ?? [];

        return [
            'input_tokens' => $usage['prompt_tokens'] ?? null,
            'output_tokens' => $usage['completion_tokens'] ?? null,
            'total_tokens' => $usage['total_tokens'] ?? null,
        ];
    }

    /**
     * Extrae tokens de una respuesta de OpenRouter
     *
     * Estructura esperada (similar a OpenAI):
     * {
     *   "usage": {
     *     "prompt_tokens": 123,
     *     "completion_tokens": 456,
     *     "total_tokens": 579
     *   }
     * }
     */
    public function extractFromOpenRouter(array $response): array
    {
        return $this->extractFromOpenAI($response);
    }

    /**
     * Extrae tokens según el proveedor
     */
    public function extract(string $provider, array $response): array
    {
        return match ($provider) {
            'gemini' => $this->extractFromGemini($response),
            'openai' => $this->extractFromOpenAI($response),
            'openrouter' => $this->extractFromOpenRouter($response),
            default => [
                'input_tokens' => null,
                'output_tokens' => null,
                'total_tokens' => null,
            ],
        };
    }
}
