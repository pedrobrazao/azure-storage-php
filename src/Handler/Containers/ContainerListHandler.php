<?php
namespace App\Handler\Containers;

use App\Service\BlobStorageService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;

final class ContainerListHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly BlobStorageService $blobStorageService
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        $response = new Response();

        return $view->render($response, 'containers/list.html.twig', [
            'containers' => $this->blobStorageService->listContainers(),
        ]);
    }
}
{

}