<?php

declare(strict_types=1);

namespace Application\Api\V2\Common;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

final class SentryMiddleware implements MiddlewareInterface
{
    // Inject sentry client
    public function process(Request $request, RequestHandler $handler): Response
    {
        // TODO: Install sentry here
        // $sentryClient->install();
        return $handler->handle($request);
    }
}
