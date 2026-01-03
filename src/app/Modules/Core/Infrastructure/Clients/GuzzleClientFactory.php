<?php

declare(strict_types=1);

namespace App\Modules\Core\Infrastructure\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

class GuzzleClientFactory
{
    private const array DEFAULT_CONFIG = [
        'headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ],
        'timeout' => 30,
        'connect_timeout' => 5,
    ];

    public function create(array $config = []): Client
    {
        return new Client(
            array_replace_recursive(
                array_merge(
                    self::DEFAULT_CONFIG,
                    ['handler' => HandlerStack::create()]
                ),
                $config
            )
        );
    }
}
