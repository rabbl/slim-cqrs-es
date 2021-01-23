<?php

declare(strict_types=1);

namespace Application\Api\V2\Messagebox;

use Core\CommandBus;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Factory\ResponseFactory;

final class MessageBoxMiddleware
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $commandBus = new CommandBus();
        // Todo handle command here

        $response = (new ResponseFactory())->createResponse(202);
        return $response;
    }
}
