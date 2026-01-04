<?php

declare(strict_types=1);

namespace App\Modules\Core\Domain\Exceptions;

use Illuminate\Support\Facades\Log;
use Throwable;

class ApiCallFailedException extends ApiException
{
    private const string MESSAGE = 'Api Call Error.';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }

    public static function forParameters(
        Throwable $throwable,
        string $method,
        string $uri,
        array $options,
        ?int $httpStatusCode = null,
        ?array $responseBody = null
    ): self {
        Log::error('API Call Failed', [
            'uri' => $uri,
            'method' => $method,
            'status_code' => $httpStatusCode,
            'response' => $responseBody, // AQUÍ veremos el mensaje real de Google
            'exception_message' => $throwable->getMessage()
        ]);
    
        $exception = new self();
        $exception->code = $httpStatusCode ?? $throwable->getCode();
        $exception->context = [
            'error' => [
                'message' => $throwable->getMessage(),
                'code' => $throwable->getCode(),
            ],
            'method' => $method,
            'uri' => $uri,
            'http_status_code' => $httpStatusCode,
            'response_body' => $responseBody,
        ];

        return $exception;
    }
}
