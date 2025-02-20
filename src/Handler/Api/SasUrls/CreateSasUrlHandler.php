<?php
namespace App\Handler\Api\SasUrls;

use App\Azure\Sas\UrlFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

final class CreateSasUrlHandler implements RequestHandlerInterface
{
    public function __construct(private UrlFactoryInterface $factory)
    {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getParsedBody();
        $data = ['url'=>(string) $this->factory->create($params['blob'])];
        $json = json_encode($data);
        
        $response = new Response(201);
        $response->getBody()->write($json);

        return $response->withAddedHeader('content-type', 'application/json');
    }
}
{

}