<?php

declare(strict_types=1);

namespace App\Modules\Core\Domain\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

abstract class ApiException extends Exception
{
    protected array $context = [];

    protected int $httpStatusCode = Response::HTTP_BAD_REQUEST;

    public function __construct(
        string $message = '',
        int $code = 0,
        ?Exception $previous = null,
        array $context = []
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
            'errors' => $this->context,
        ], $this->httpStatusCode);
    }
}

