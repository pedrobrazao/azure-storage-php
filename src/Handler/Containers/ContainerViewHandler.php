<?php
namespace App\Handler\Containers;

use App\Handler\RequestAttibutesTrait;
use App\Service\BlobStorageService;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;

final class ContainerViewHandler implements RequestHandlerInterface
{
    use RequestAttibutesTrait;
    
    public function __construct(
        private readonly BlobStorageService $blobStorageService
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (null === $name = $this->getRequestArgument($request, 'name')) {
            throw new \RuntimeException('Unspecified container name.');
        }

        if (null === $container = $this->blobStorageService->getContainer($name)) {
            return new Response(StatusCodeInterface::STATUS_NOT_FOUND);
        }

        $view = Twig::fromRequest($request);
        $response = new Response();

        return $view->render($response, 'containers/view.html.twig', [
            'container' => $container,
        ]);
    }
}
{

}