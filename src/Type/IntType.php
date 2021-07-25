<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Type;

final class IntType extends NumberType
{
    public function cast(mixed $value): int
    {
        return (int) $value;
    }

    public function getDefaultValue(): int
    {
        return 0;
    }

    public function isBuiltIn(): bool
    {
        return true;
    }

    public function __toString(): string
    {
        return 'int';
    }
}
