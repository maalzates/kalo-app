<?php

declare(strict_types=1);

namespace App\Modules\Core\Infrastructure\Clients;

use App\Modules\Core\Domain\Exceptions\ApiCallFailedException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

abstract class ApiClientAbstract
{
    public function __construct(protected readonly Client $client)
    {
    }

    /**
     * @throws ApiCallFailedException
     */
    protected function get(string $uri, array $params = []): array
    {
        return $this->request(
            Request::METHOD_GET,
            $uri,
            [
                RequestOptions::QUERY => $params,
            ]
        );
    }

    /**
     * @throws ApiCallFailedException
     */
    protected function post(string $uri, array $options = []): array
    {
        return $this->request(
            Request::METHOD_POST,
            $uri,
            $options
        );
    }

    /**
     * @throws ApiCallFailedException
     */
    protected function patch(string $uri, array $options = []): array
    {
        return $this->request(
            Request::METHOD_PATCH,
            $uri,
            $options
        );
    }

    /**
     * @throws ApiCallFailedException
     */
    protected function put(string $uri, array $options = []): array
    {
        return $this->request(
            Request::METHOD_PUT,
            $uri,
            $options
        );
    }

    /**
     * @throws ApiCallFailedException
     */
    protected function delete(string $uri, array $options = []): array
    {
        return $this->request(
            Request::METHOD_DELETE,
            $uri,
            $options
        );
    }

    /**
     * @throws ApiCallFailedException
     */
    protected function getRaw(string $uri, array $params = []): string
    {
        return $this->requestRaw(
            Request::METHOD_GET,
            $uri,
            [
                RequestOptions::QUERY => $params,
            ]
        );
    }

    /**
     * @throws ApiCallFailedException
     */
    private function request(string $method, string $uri, array $options = []): array
    {
        try {
            return $this->parseResponse(
                $this->client->request(
                    $method,
                    $uri,
                    $options
                )
            );
        } catch (RequestException $exception) {
            $response = $exception->getResponse();
            $statusCode = $response?->getStatusCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR;

            throw ApiCallFailedException::forParameters(
                $exception,
                $method,
                $uri,
                $options,
                $statusCode,
                $this->tryDecodeJsonBody($response),
            );
        } catch (Throwable $exception) {
            Log::error(sprintf('Api Call to "%s" failed', $uri), [
                'error' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            throw ApiCallFailedException::forParameters($exception, $method, $uri, $options);
        }
    }

    /**
     * @throws ApiCallFailedException
     */
    private function requestRaw(string $method, string $uri, array $options = []): string
    {
        try {
            return $this
                ->client
                ->request($method, $uri, $options)
                ->getBody()
                ->getContents();
        } catch (RequestException $exception) {
            $response = $exception->getResponse();
            $statusCode = $response?->getStatusCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR;

            throw ApiCallFailedException::forParameters(
                $exception,
                $method,
                $uri,
                $options,
                $statusCode,
                $this->tryDecodeJsonBody($response),
            );
        } catch (Throwable $exception) {
            Log::error(sprintf('Api Call to "%s" failed', $uri), [
                'error' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            throw ApiCallFailedException::forParameters($exception, $method, $uri, $options);
        }
    }

    /**
     * @throws JsonException
     */
    private function parseResponse(ResponseInterface $response): array
    {
        $body = $response->getBody()->getContents();
        if (empty($body)) {
            return [];
        }

        return $this->decodeJson($body);
    }

    private function tryDecodeJsonBody(?ResponseInterface $response): ?array
    {
        if (!$response) {
            return null;
        }

        try {
            return $this->decodeJson((string) $response->getBody());
        } catch (JsonException) {
            // If the response body is not valid JSON, leave it as null
            return null;
        }
    }

    /**
     * @throws JsonException
     */
    private function decodeJson(string $body): array
    {
        return (array) json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    }
}
