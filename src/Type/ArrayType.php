<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Type;

final class ArrayType extends Type implements Defaultable, Castable
{
    /** @phpstan-ignore-next-line */
    public function getDefaultValue(): array
    {
        return [];
    }

    /** @phpstan-ignore-next-line */
    public function cast(mixed $value): array
    {
        return (array) $value;
    }

    public function isBuiltIn(): bool
    {
        return true;
    }

    public function __toString(): string
    {
        return 'array';
    }
}
