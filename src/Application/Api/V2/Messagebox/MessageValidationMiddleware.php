<?php

declare(strict_types=1);

namespace Application\Api\V2\Messagebox;

use Exception;
use Core\AbstractCommand;
use Application\Api\V2\User\Command\CreateUserCommand;
use Application\Api\V2\User\CommandHandler\CreateUserCommandHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use RuntimeException;
use Slim\Psr7\Factory\ResponseFactory;

final class MessageValidationMiddleware implements MiddlewareInterface
{
    private array $availableCommands = [];
    private array $commandsWithHandlers = [
        CreateUserCommand::class => CreateUserCommandHandler::class
    ];

    public function process(Request $request, RequestHandler $handler): Response
    {
        $this->setAvailableCommands($this->commandsWithHandlers);

        try {
            $this->assertIsValidRequest($request);
        } catch (Exception $e) {
            $response = (new ResponseFactory())->createResponse(422);
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));

            return $response;
        }

        return $handler->handle($request);
    }


    /**
     * @param Request $request
     * @throws Exception
     */
    private function assertIsValidRequest(Request $request): void
    {
        if (0 !== strpos($request->getHeader('Content-Type')[0], 'application/json')) {
            throw new RuntimeException('Expecting Header: Content-Type: application/json');
        }

        $body = $request->getParsedBody();
        if (null === $body) {
            throw new RuntimeException('Invalid JSON-Data received.');
        }

        $message_name = $body['message_name'] ?? null;
        if (!$message_name) {
            throw new RuntimeException(sprintf('Parameter message_name not given or null.'));
        }

        if (!array_key_exists($message_name, $this->availableCommands)) {
            throw new RuntimeException(
                sprintf(
                    'MessageName: %s not in the list of available commands. Available commands are: %s.',
                    $message_name,
                    implode(', ', array_keys($this->availableCommands))
                )
            );
        }

        $payload = $body['payload'] ?? null;

        if (null === $payload) {
            throw new RuntimeException('Parameter payload expected.');
        }
    }

    private function setAvailableCommands(array $availableCommands): void
    {
        /**
         * @var AbstractCommand $command
         * @var  $handler
         */
        foreach ($availableCommands as $command => $handler) {
            $this->availableCommands[$command::messageName()] = $command;
        }
    }
}

