<?php

declare(strict_types=1);

namespace Core;

use DateTimeImmutable;
use Exception;

abstract class AbstractCommand implements CommandInterface
{
    protected array $metadata = [];

    protected DateTimeImmutable $dateTime;

    abstract public static function fromPayload(array $payload);

    public static function messageName(): string
    {
        return str_replace('Command', '', lcfirst(substr(static::class, strrpos(static::class, '\\') + 1)));
    }

    public static function jsonSchema(): ?string
    {
        return null;
    }

    /**
     * @throws Exception
     */
    protected function __construct()
    {
        $this->dateTime = new DateTimeImmutable('now');
    }

    public function withAddedMetadata(string $key, $value): void
    {
        $this->metadata[$key] = $value;
    }

    public function metadata(): array
    {
        return $this->metadata;
    }

    public function metadataByKey(string $key)
    {
        return $this->metadata[$key] ?? null;
    }

    public function dateTime(): DateTimeImmutable
    {
        return $this->dateTime;
    }
}
