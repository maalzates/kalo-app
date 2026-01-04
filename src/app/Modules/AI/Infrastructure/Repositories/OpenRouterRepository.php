<?php

declare(strict_types=1);

namespace App\Modules\AI\Infrastructure\Repositories;

use App\Modules\AI\Domain\Contracts\OpenRouterRepositoryInterface;
use App\Modules\AI\Domain\Exceptions\OpenRouterClientException;
use App\Modules\AI\Infrastructure\Clients\OpenRouterClient;
use App\Modules\Core\Domain\Exceptions\ApiCallFailedException;

class OpenRouterRepository implements OpenRouterRepositoryInterface
{
    public function __construct(
        private readonly OpenRouterClient $client
    ) {}

    public function analyzeFoodImage(string $imageBase64, string $mimeType): array
    {
        try {
            $systemInstruction = config('prompts.gemini.food_analysis.system_instruction');
            $userInstruction = config('prompts.gemini.food_analysis.user_instruction');

            $response = $this->client->chatCompletions([
                'model' => config('services.openrouter.model', 'google/gemini-pro-1.5-exp'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemInstruction,
                    ],
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => $userInstruction,
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
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.4,
                'top_p' => 0.95,
            ]);

            return json_decode($response['choices'][0]['message']['content'], true);
        } catch (ApiCallFailedException $exception) {
            throw OpenRouterClientException::fromApiCallFailedException($exception);
        }
    }
}
