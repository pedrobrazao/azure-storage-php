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

final class BlobCreateHandler implements RequestHandlerInterface
{    
    use RequestAttibutesTrait;

    public function __construct(
        private readonly BlobStorageService $blobStorageService
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $form = [
            'name' => '',
            'contents' => '',
        ];

        $error = null;
        $response = new Response();

        $containerName = $this->getRequestArgument($request, 'container');

if (!$this->blobStorageService->containerExists($containerName)) {
    return $response->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);
}

        if ('POST' === strtoupper($request->getMethod())) {
            $form = $request->getParsedBody();
        if (null === $error) {
            $this->blobStorageService->writeBlob($containerName, $form['name'], $form['contents']);

            $url = RouteContext::fromRequest($request)->getRouteParser()->urlFor('blob_view', ['container' => $containerName, 'blob' => $form['name']]);

            return $response->withStatus(StatusCodeInterface::STATUS_FOUND)->withHeader('Location', $url);
        }
        }

        $view = Twig::fromRequest($request);

        return $view->render($response, 'blobs/create.html.twig', [
            'container' => $containerName,
            'form' => $form,
            'error' => $error,
        ]);
    }
}
