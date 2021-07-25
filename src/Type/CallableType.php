<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Type;

final class CallableType extends Type
{
    public function isAssignable(Type $other): bool
    {
        if ($other instanceof $this) {
            return true;
        }

        if ($other instanceof ObjectType && $other->isInvokable()) {
            return true;
        }

        return false;
    }

    public function isBuiltIn(): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return 'callable';
    }
}
