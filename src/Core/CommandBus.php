<?php

declare(strict_types=1);

namespace Core;

final class CommandBus
{

    private array $commandsWithHandlersMap = [];

    public function registerCommand(CommandInterface $command, CommandHandlerInterface $handler)
    {
        $this->commandsWithHandlersMap[get_class($command)] = get_class($handler);
    }

    public function dispatch(CommandInterface $command): void
    {
        foreach ($this->commandsWithHandlersMap as $commandClass => $commandHandlerClass) {
            if ($command instanceof $commandClass) {
                (new $commandHandlerClass())->handle($command);
            }
        }
    }

    public function commands(): array
    {
        return array_keys($this->commandsWithHandlersMap);
    }
}