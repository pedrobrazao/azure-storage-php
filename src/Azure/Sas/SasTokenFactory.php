<?php

namespace App\Azure\Sas;

use InvalidArgumentException;

final class SasTokenFactory implements SasTokenFactoryInterface
{
    public function __construct(
        private readonly string $accountName,
        private readonly string $accountKey
    ) {}

    public function generateSasToken(
        string $permissions, 
        string $expiryDateTime,
        string $services = self::SERVICES,
        string $resourceType = self::RESOURCE_TYPE,
        string $version = self::VERSION,
        string $protocol = self::PROTOCOL,
        string $ipRange = self::IP_RANGE
        ): string {
            $stringToSign = $this->accountName . "\n"
            . $permissions . "\n"
            . $services . "\n"
            . $resourceType . "\n"
            . "\n" // start date time (empty)
            . $expiryDateTime . "\n"
            . $ipRange . "\n"
            . $protocol . "\n"
            . $version . "\n";

            $decodedKey = base64_decode($this->accountKey, true);

            if (false === $decodedKey) {
                throw new InvalidArgumentException('Invalid account key.');
            }

            $hashSignature = hash_hmac('sha256', $stringToSign, $decodedKey);
            $token = base64_encode($hashSignature);

            return $token;
        }

}