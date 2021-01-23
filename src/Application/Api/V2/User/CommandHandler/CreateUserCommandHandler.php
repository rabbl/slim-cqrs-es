<?php

declare(strict_types=1);

namespace Application\Api\V2\User\CommandHandler;

use Application\Api\V2\User\Command\CreateUserCommand;
use Core\CommandHandlerInterface;

final class CreateUserCommandHandler implements CommandHandlerInterface
{
    // Todo:
    // Why the error here: Declaration mus be compatible with ...
    public function handle(CreateUserCommand $command): void
    {
        // Todo: Implement something here
        // ...
    }
}
