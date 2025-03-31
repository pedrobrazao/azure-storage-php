<?php

namespace App\Handler;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\Route ;

trait RequestAttibutesTrait
{
    private function getRoute(ServerRequestInterface $request): ?Route
    {
        return $request->getAttribute('__route__');
    }

    private function getRequestArgument(ServerRequestInterface $request, string $name): ?string
    {
        if (null === $route = $this->getRoute($request)) {
            return null;
        }

        return $route->getArgument($name);
    }
}