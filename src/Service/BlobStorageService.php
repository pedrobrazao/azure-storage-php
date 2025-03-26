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
}