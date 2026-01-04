<?php

declare(strict_types=1);

namespace App\Modules\AI\Infrastructure\Repositories;

use App\Modules\AI\Domain\Contracts\GeminiRepositoryInterface;
use App\Modules\AI\Domain\Exceptions\GeminiException;
use App\Modules\AI\Infrastructure\Clients\GeminiClient;
use Illuminate\Support\Facades\Log;
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

            $response = $this->client->generateContent([
                'system_instruction' => [
                    'parts' => [
                        ['text' => $systemInstruction],
                    ],
                ],
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $userInstruction],
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
                    'response_mime_type' => 'application/json',
                    'temperature' => 0.4,
                    'top_p' => 0.95,
                    'top_k' => 40,
                ],
            ]);

            return json_decode($response['candidates'][0]['content']['parts'][0]['text'], true);
        } catch (Throwable $exception) {
            $detailedError = $exception->getMessage();

            if ($exception instanceof \GuzzleHttp\Exception\ClientException) {
                $responseBody = $exception->getResponse()->getBody()->getContents();
                $detailedError = "API Response: " . $responseBody;
            }

            Log::error("Gemini detailed failure: " . $detailedError);

            throw GeminiException::forFoodAnalysisCall($imageBase64, $mimeType, $exception, $detailedError);
        }
    }
}
