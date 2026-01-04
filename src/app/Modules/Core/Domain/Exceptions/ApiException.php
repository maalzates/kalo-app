<?php

declare(strict_types=1);

namespace App\Modules\Core\Domain\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ApiException extends Exception
{
    private const string DEFAULT_MESSAGE = 'Unknown Error Occurred.';

    protected array $context = [];

    public function __construct(?string $message = null, int $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        parent::__construct($message ?? self::DEFAULT_MESSAGE, $code);
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public static function fromApiCallFailedException(ApiCallFailedException $apiCallFailedException): static
    {
        /* @phpstan-ignore-next-line */
        $exception = new static();
        $exception->code = $apiCallFailedException->getCode();
        $exception->message = $apiCallFailedException->getMessage();
        $exception->context = $apiCallFailedException->getContext();

        return $exception;
    }
}
