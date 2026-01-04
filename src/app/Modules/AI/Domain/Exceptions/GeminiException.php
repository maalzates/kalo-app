<?php

declare(strict_types=1);

namespace App\Modules\AI\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Symfony\Component\HttpFoundation\Response;

class GeminiException extends ApiException
{
    private const string DEFAULT_MESSAGE = 'Gemini API request failed.';

    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? self::DEFAULT_MESSAGE, Response::HTTP_SERVICE_UNAVAILABLE);
    }

    public static function forFoodAnalysisCall(string $imageBase64, string $mimeType): self
    {
        $exception = new self('Failed to analyze food image with Gemini API', 0, null, [
            'mimeType' => $mimeType,
            'imageSize' => strlen($imageBase64),
        ]);

        return $exception;
    }
}
