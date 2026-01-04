<?php

declare(strict_types=1);

namespace App\Modules\AI\Infrastructure\Repositories;

use App\Modules\AI\Domain\Contracts\GeminiRepositoryInterface;
use App\Modules\AI\Domain\Exceptions\GeminiException;
use App\Modules\AI\Infrastructure\Clients\GeminiClient;
use Throwable;

class GeminiRepository implements GeminiRepositoryInterface
{
    public function __construct(
        private readonly GeminiClient $client
    ) {}

    public function analyzeFoodImage(string $imageBase64, string $mimeType): array
    {
        try {
            $systemInstruction = config('prompts.gemini.food_analysis.system_instruction');
            $userInstruction = config('prompts.gemini.food_analysis.user_instruction');

            // En la versión v1 estable, mezclamos la instrucción de sistema con la del usuario 
            // ya que el campo 'system_instruction' a menudo da error 400.
            $combinedPrompt = "IMPORTANT INSTRUCTIONS:\n" . $systemInstruction . "\n\nUSER REQUEST:\n" . $userInstruction;

            $response = $this->client->generateContent([
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
                    // Nota: Se eliminó 'response_mime_type' porque v1 a veces no lo reconoce 
                    // Tu prompt ya exige JSON, así que Gemini cumplirá.
                    'temperature' => 0.2, // Bajamos un poco la temperatura para que sea más exacto con el JSON
                    'top_p' => 0.95,
                    'top_k' => 40,
                ],
            ]);

            $textResponse = $response['candidates'][0]['content']['parts'][0]['text'] ?? '';

            // Limpieza de Markdown: Gemini a veces envuelve el JSON en bloques ```json ... ```
            $cleanJson = preg_replace('/^```json\s*|```$/m', '', $textResponse);

            return json_decode(trim($cleanJson), true) ?? throw new \Exception("Invalid JSON response from AI");

        } catch (Throwable $exception) {
            throw GeminiException::forFoodAnalysisCall($imageBase64, $mimeType, $exception);
        }
    }
}
