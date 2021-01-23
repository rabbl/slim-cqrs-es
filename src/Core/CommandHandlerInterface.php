<?php

declare(strict_types=1);

namespace Core;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command): void;
}