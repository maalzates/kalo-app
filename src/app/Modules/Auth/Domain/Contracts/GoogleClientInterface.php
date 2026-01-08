<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Contracts;

interface GoogleClientInterface
{
    /**
     * Verify a Google ID Token
     *
     * @param string $idToken The ID token to verify
     * @return array|false The payload if valid, false otherwise
     */
    public function verifyIdToken(string $idToken): array|false;
}
