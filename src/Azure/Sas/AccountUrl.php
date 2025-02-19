<?php

namespace App\Azure\Sas;

use Psr\Http\Message\UriInterface;
use Slim\Psr7\Uri;

class AccountUrl
{
    public const HOST_DOMAIN = '.blob.core.windows.net';
    public const DEFAULT_TTL = 3600;
    public const DEFAULT_PERMISSIONS = 'r';
    public const DEFAULT_SIGNED_IP = '';
    public const DEFAULT_PROTOCOL = 'https';
    
    private string $sv = '2023-11-03';
    private string $ss= 'b';
    private string $srt = 'sco';
    private string $sp = self::DEFAULT_PERMISSIONS; // rwdlac
    private string $sip = self::DEFAULT_SIGNED_IP;
    private string $spr = self::DEFAULT_PROTOCOL;

public function __construct(
    private string $account,
    private string $container,
    private string $key
    ) {}

    public function create(string $blob, int $ttl = self::DEFAULT_TTL, string $permissions = self::DEFAULT_PERMISSIONS): UriInterface
    {
        $thissp = $permissions;

        $host = $this->account . self::HOST_DOMAIN;
        $path = $this->container . '/' . urlencode($blob);

        $startDate = date('Y-m-d\TH:i:s', time() - 300) . 'Z';
        $expiryDate = date('Y-m-d\TH:i:s', time() + $ttl) . 'Z';
        $signal = $this->buildSignature($startDate, $expiryDate);

        $query = http_build_query([
            'sv' => $this->sv,
            'ss' => $this->ss,
            'srt' => $this->srt,
            'sp' => $this->sp,
            'se' => $expiryDate,
            'st' => $startDate,
            'spr' => $this->spr,
            'signal' => $signal,
        ]);

        return new Uri('https', $host, null, $path, $query);
    }

    private function buildSignature(string $startDate, string $expiryDate): string
    {
        $stringToSign = $this->account . "\n"
        . $this->sp . "\n"
        . $this->ss . "\n"
        . $this->srt . "\n"
        . $startDate . "\n"
        . $expiryDate . "\n"
        . $this->sip . "\n"
        . $this->spr . "\n"
        . $this->sv . "\n";

        $signature = base64_encode(hash_hmac('sha256', $stringToSign, base64_decode($this->key), true));

return $signature;
    }
}