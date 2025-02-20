<?php

namespace App\Azure\Sas;

use Psr\Http\Message\UriInterface;

interface UrlFactoryInterface
{
    public function create(string $blob, int $ttl = self::DEFAULT_TTL, string $permissions = self::DEFAULT_PERMISSIONS): UriInterface;
}