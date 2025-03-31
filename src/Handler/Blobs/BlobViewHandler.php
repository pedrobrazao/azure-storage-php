<?php
namespace App\Handler\Blobs;

use App\Handler\RequestAttibutesTrait;
use App\Service\BlobStorageService;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

final class BlobViewHandler implements RequestHandlerInterface
{    
    use RequestAttibutesTrait;

    public function __construct(
        private readonly BlobStorageService $blobStorageService
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();

        $containerName = $this->getRequestArgument($request, 'container');
        $blobName = $this->getRequestArgument($request, 'blob');

if (!$this->blobStorageService->containerExists($containerName)) {
    return $response->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);
}

$blob = $this->blobStorageService->getBlob($containerName, $blobName);

        $view = Twig::fromRequest($request);

        return $view->render($response, 'blobs/view.html.twig', [
            'container' => $containerName,
            'blob' => $blob,
        ]);
    }
}
