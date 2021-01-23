<?php

declare(strict_types=1);

namespace Application\Api\V2\User\Command;

use Core\AbstractCommand;
use Core\CommandInterface;

final class CreateUserCommand extends AbstractCommand
{
    private string $username;
    private string $password;
    private array $roles;
    private bool $enabled;

    public static function fromParams(
        string $username,
        string $password,
        array $roles = ['ROLE_USER'],
        bool $enabled = true
    ): self
    {
        $self = new self();
        $self->username = $username;
        $self->password = $password;
        $self->roles = $roles;
        $self->enabled = $enabled;

        return $self;
    }

    public static function fromPayload(array $payload): self
    {
        $self = new self();
        $self->username = $payload['username'];
        $self->password = $payload['password'];
        $self->roles = ['ROLE_USER'];
        $self->enabled = true;

        return $self;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function roles(): array
    {
        return $this->roles;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
