<?php

declare(strict_types=1);

namespace App\Modules\AI\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class GeminiException extends ApiException
{
    private const string DEFAULT_MESSAGE = 'Gemini API request failed.';

    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? self::DEFAULT_MESSAGE, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public static function forFoodAnalysisCall(string $imageBase64, string $mimeType, Throwable $throwable): self
    {
        $code = $throwable->getCode() > 0 ? $throwable->getCode() : 500;

        $exception = new self('Failed to analyze food image with Gemini API', $code, $throwable);

        $exception->context = [
            'mimeType' => $mimeType,
            'imageSize' => strlen($imageBase64),
            'error' => [
                'exception' => $throwable->getMessage(),
                'trace' => $throwable->getTraceAsString(),
                'code' => $code,
            ],
        ];

        return $exception;
    }
}
