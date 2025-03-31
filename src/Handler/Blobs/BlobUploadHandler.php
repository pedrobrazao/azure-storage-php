<?php
namespace App\Handler\Blobs;

use App\Handler\RequestAttibutesTrait;
use App\Service\BlobStorageService;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

final class BlobUploadHandler implements RequestHandlerInterface
{    
    use RequestAttibutesTrait;

    public function __construct(
        private readonly BlobStorageService $blobStorageService
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $error = null;
        $response = new Response();

        $containerName = $this->getRequestArgument($request, 'container');

if (!$this->blobStorageService->containerExists($containerName)) {
    return $response->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);
}

        if ('POST' === strtoupper($request->getMethod())) {
            /** @var U      ploadedFileInterface $upload */
            $upload = $request->getUploadedFiles()['blob'] ?? null;

            if (null === $upload || UPLOAD_ERR_OK !== $upload->getError()) {
            $error = 'An error occurred during the blob upload.';
            }
            
        if (null === $error) {
            $blobName = $upload->getClientFilename();
            $fileName = $upload->getFilePath();
            $this->blobStorageService->uploadBlob($containerName, $blobName, $fileName);

            $url = RouteContext::fromRequest($request)->getRouteParser()->urlFor('blob_view', ['container' => $containerName, 'blob' => $blobName]);

            return $response->withStatus(StatusCodeInterface::STATUS_FOUND)->withHeader('Location', $url);
        }
        }

        $view = Twig::fromRequest($request);

        return $view->render($response, 'blobs/upload.html.twig', [
            'container' => $containerName,
            'error' => $error,
        ]);
    }
}
