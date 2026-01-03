<?php

declare(strict_types=1);

namespace App\Modules\Core\Domain\Exceptions;

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
        $exception = new self();
        $exception->code = $httpStatusCode ?? $throwable->getCode();
        $exception->context = [
            'error' => [
                'message' => $throwable->getMessage(),
                'trace' => $throwable->getTraceAsString(),
                'code' => $throwable->getCode(),
                'line' => $throwable->getLine(),
                'file' => $throwable->getFile(),
            ],
            'method' => $method,
            'uri' => $uri,
            'options' => $options,
            'http_status_code' => $httpStatusCode,
            'response_body' => $responseBody,
        ];

        return $exception;
    }
}
