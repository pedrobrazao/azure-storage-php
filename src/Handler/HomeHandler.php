<?php
namespace App\Handler;

use  AzureOss\Storage\Blob\BlobServiceClient;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;

final class HomeHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly BlobServiceClient $blobServiceClient
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $containers = [];

        foreach ($this->blobServiceClient->getBlobContainers() as $container) {
            $containers[] = $container;
        }
        

        $view = Twig::fromRequest($request);

        $response = new Response();

        return $view->render($response, 'home.html.twig', [
            'containers' => $containers,
        ]);
    }
}
{

}