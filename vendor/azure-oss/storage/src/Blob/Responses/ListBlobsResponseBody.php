<?php

declare(strict_types=1);

namespace AzureOss\Storage\Blob\Responses;

use AzureOss\Storage\Blob\Models\Blob;
use AzureOss\Storage\Blob\Models\BlobPrefix;

/**
 * @internal
 */
final class ListBlobsResponseBody
{
    /**
     * @param  Blob[]  $blobs
     * @param  BlobPrefix[]  $blobPrefixes
     */
    private function __construct(
        public readonly string $prefix,
        public readonly string $marker,
        public readonly int $maxResults,
        public readonly string $nextMarker,
        public readonly array $blobs,
        public readonly array $blobPrefixes,
        public readonly ?string $delimiter = null,
    ) {}

    public static function fromXml(\SimpleXMLElement $xml): self
    {
        $blobs = [];
        $blobPrefixes = [];

        foreach ($xml->Blobs->children() as $blobOrPrefix) {
            switch ($blobOrPrefix->getName()) {
                case 'Blob':
                    $blobs[] = Blob::fromXml($blobOrPrefix);
                    break;
                case 'BlobPrefix':
                    $blobPrefixes[] = BlobPrefix::fromXml($blobOrPrefix);
                    break;
            }
        }

        return new self(
            (string) $xml->Prefix,
            (string) $xml->Marker,
            (int) $xml->MaxResults,
            (string) $xml->NextMarker,
            $blobs,
            $blobPrefixes,
            (string) $xml->Delimiter,
        );
    }
}
