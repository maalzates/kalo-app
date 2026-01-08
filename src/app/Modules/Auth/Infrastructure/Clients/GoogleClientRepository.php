<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infrastructure\Clients;

use App\Modules\Auth\Domain\Contracts\GoogleClientInterface;
use Google_Client;

class GoogleClientRepository implements GoogleClientInterface
{
    private Google_Client $client;

    public function __construct(Google_Client $client)
    {
        $this->client = $client;
    }

    /**
     * Verify a Google ID Token
     *
     * @param string $idToken The ID token to verify
     * @return array|false The payload if valid, false otherwise
     */
    public function verifyIdToken(string $idToken): array|false
    {
        return $this->client->verifyIdToken($idToken);
    }
}
