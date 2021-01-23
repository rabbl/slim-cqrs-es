<?php

declare(strict_types=1);

namespace Core;

use DateTimeImmutable;

interface CommandInterface
{
    public static function fromPayload(array $payload);

    public static function messageName(): string;

    public static function jsonSchema(): ?string;

    public function withAddedMetadata(string $key, $value): void;

    public function metadata(): array;

    public function metadataByKey(string $key);

    public function dateTime(): DateTimeImmutable;
}