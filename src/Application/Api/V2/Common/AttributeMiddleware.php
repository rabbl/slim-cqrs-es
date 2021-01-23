<?php

declare(strict_types=1);

namespace Application\Api\V2\Common;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Routing\RouteContext;

final class AttributeMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        // Resolve user ID in this scope
        if (null === $route || 'ralf' === $route->getArgument('name')) {
            return (new ResponseFactory())->createResponse(404);
        }

        $response = $handler->handle($request->withAttribute('test', ' immer'));

        return $response->withAddedHeader('X-Middleware', [get_class($this)]);
    }
}
