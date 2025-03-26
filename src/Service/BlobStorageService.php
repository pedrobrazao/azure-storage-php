<?php
namespace App\Service;

use  AzureOss\Storage\Blob\Models\BlobContainer;
use  AzureOss\Storage\Blob\BlobServiceClient;

final class BlobStorageService
{
    public function __construct(
        private readonly BlobServiceClient $blobServiceClient
    ) {}

    /**
     * @return BlobContainer[]
     */
    public function listContainers(): array
    {
        $containers = [];

        foreach ($this->blobServiceClient->getBlobContainers() as $container) {
            $containers[] = $container;
        }
        
        return $containers;
    }

    public function getContainer(string $name): ?BlobContainer
    {
        foreach ($this->blobServiceClient->getBlobContainers() as $container) {
            if ($container->name === $name) {
                return $container;
            }
}

        return null;
    }

    public function containerExists(string $name): bool
    {
        foreach ($this->blobServiceClient->getBlobContainers() as $container) {
            if ($container->name === $name) {
                return true;
            }
}

        return false;
    }

    public function createContainer(string $name): void{
        $containerClient = $this->blobServiceClient->getContainerClient($name);
        $containerClient->create();
    }

    public function listBlobs(string $containerName, string $prefix = null): array{
        $blobs = [];

        foreach ($this->blobServiceClient->getContainerClient($containerName)->getBlobs($prefix) as $blob) {
            $blobs[] = $blob;
        }

        return $blobs;
    }
    public function writeBlob(string $containerName, string $blobName, string $contents): void
    {
$blobClient = $this->blobServiceClient->getContainerClient($containerName)->getBlobClient($blobName);
$blobClient->upload($contents);
    }

    public function uploadBlob(string $containerName, string $blobName, string $fileName): void
    {
$blobClient = $this->blobServiceClient->getContainerClient($containerName)->getBlobClient($blobName);
$file = fopen($fileName, 'r+');
$blobClient->upload($file);
    }
}