<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Type;

final class BoolType extends ScalarType implements Defaultable, Castable
{
    public function isAssignable(Type $other): bool
    {
        return $other instanceof $this || $other instanceof FalseType;
    }

    public function cast(mixed $value): bool
    {
        return (bool) $value;
    }

    public function getDefaultValue(): bool
    {
        return false;
    }

    public function isBuiltIn(): bool
    {
        return true;
    }

    public function __toString(): string
    {
        return 'bool';
    }
}
