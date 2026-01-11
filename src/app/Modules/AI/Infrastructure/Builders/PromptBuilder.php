<?php

declare(strict_types=1);

namespace App\Modules\AI\Infrastructure\Builders;

class PromptBuilder
{
    /**
     * Obtiene el prompt del config según tipo de comida
     *
     * @param  string  $foodType  'ingredient' | 'recipe'
     * @return array{system_instruction: string, user_instruction: string}
     */
    private function getPromptConfig(string $foodType): array
    {
        return config("prompts.{$foodType}");
    }

    /**
     * Formatea el prompt para Gemini AI
     *
     * @param  string  $imageBase64
     * @param  string  $mimeType
     * @param  string  $foodType
     * @return array<string, mixed>
     */
    public function formatForGemini(string $imageBase64, string $mimeType, string $foodType = 'ingredient'): array
    {
        $prompt = $this->getPromptConfig($foodType);

        // Gemini requiere combinar system + user en un solo texto
        $combinedPrompt = "IMPORTANT INSTRUCTIONS:\n"
            .$prompt['system_instruction']
            ."\n\nUSER REQUEST:\n"
            .$prompt['user_instruction'];

        return [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $combinedPrompt],
                        [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data' => $imageBase64,
                            ],
                        ],
                    ],
                ],
            ],
            'generationConfig' => [
                'temperature' => 0.2,
                'top_p' => 0.95,
                'top_k' => 40,
            ],
        ];
    }

    /**
     * Formatea el prompt para OpenAI
     *
     * @param  string  $imageBase64
     * @param  string  $mimeType
     * @param  string  $foodType
     * @return array<string, mixed>
     */
    public function formatForOpenAI(string $imageBase64, string $mimeType, string $foodType = 'ingredient'): array
    {
        $prompt = $this->getPromptConfig($foodType);

        // OpenAI sí permite separar system y user
        return [
            'model' => 'gpt-4-vision-preview',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $prompt['system_instruction'],
                ],
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $prompt['user_instruction'],
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => "data:{$mimeType};base64,{$imageBase64}",
                            ],
                        ],
                    ],
                ],
            ],
            'max_tokens' => 1000,
            'temperature' => 0.2,
        ];
    }
}
