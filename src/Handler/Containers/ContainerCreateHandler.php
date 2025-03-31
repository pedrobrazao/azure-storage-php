<?php
namespace App\Handler\Containers;

use App\Service\BlobStorageService;
use App\Validator\ContainerNameValidator;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

final class ContainerCreateHandler implements RequestHandlerInterface
{    
    public function __construct(
        private readonly BlobStorageService $blobStorageService
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $form = [
            'name' => '',
        ];

        $error = null;
        $response = new Response();
        
        if ('POST' === strtoupper($request->getMethod())) {
            $form = $request->getParsedBody();
            $validator = new ContainerNameValidator($form['name']);
            
            if (!$validator->isValid()) {
                $error = $validator->getError();
            }

        if (null === $error && $this->blobStorageService->containerExists($form['name'])) {
            $error = sprintf('Container with name "%s" already exists.', $form['name']);
        }

        if (null === $error) {
            $this->blobStorageService->createContainer($form['name']);

            $url = RouteContext::fromRequest($request)->getRouteParser()->urlFor('container_list');

            return $response->withStatus(StatusCodeInterface::STATUS_FOUND)->withHeader('Location', $url);
        }
        }

        $view = Twig::fromRequest($request);

        return $view->render($response, 'containers/create.html.twig', [
            'form' => $form,
            'error' => $error,
        ]);
    }
}
