<?php

declare(strict_types=1);

namespace Application\Api\V2\Common;

use Application\Api\V2\Printer\PrinterService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class MyMiddleware
{
    private PrinterService $printer;

    public function __construct(PrinterService $printer)
    {
        $this->printer = $printer;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->printer->print();
        $response->getBody()->write('Läuft noch!' . $request->getAttribute('test'));
        return $response->withAddedHeader('X-Middleware', [get_class($this)]);
    }
}
