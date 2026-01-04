<?php

declare(strict_types=1);

namespace App\Modules\AI\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\ApiCallFailedException;
use App\Modules\Core\Domain\Exceptions\ApiException;

class OpenRouterClientException extends ApiException
{
    private const string DEFAULT_MESSAGE = 'OpenRouter Client Exception.';

    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? self::DEFAULT_MESSAGE);
    }

    public static function fromApiCallFailedException(ApiCallFailedException $exception): self
    {
        $openRouterException = new self('OpenRouter API call failed');
        $openRouterException->code = $exception->getCode();
        $openRouterException->context = $exception->getContext();

        return $openRouterException;
    }
}
