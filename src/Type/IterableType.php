<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Type;

use ReflectionClass;

final class IterableType extends Type
{
    public function isAssignable(Type $other): bool
    {
        if ($other instanceof $this) {
            return true;
        }

        if ($other instanceof ObjectType) {
            /** @phpstan-ignore-next-line */
            return (new ReflectionClass($other->getFullQualifiedName()))->isIterable();
        }

        return false;
    }

    public function isBuiltIn(): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return 'iterable';
    }
}
