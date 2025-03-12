<?php

namespace App\Azure\Sas;

use Psr\Http\Message\UriInterface;

interface SasUrlFactoryInterface
{
    public const SERVICES = 'b'; // b = blob
    public const RESOURCE_TYPE = 'sco'; // sco = signed container object
    public const PROTOCOL = 'https';
    public const IP_RANGE = '';
    public const VERSION = '2020-04-08';

    public function generateSasUrl(
        string $blobName,
        string $permissions, 
        int $expirySeconds = 3600,
        string $services = self::SERVICES,
        string $resourceType = self::RESOURCE_TYPE,
        string $version = self::VERSION,
        string $protocol = self::PROTOCOL,
        string $ipRange = self::IP_RANGE
        ): UriInterface;
}