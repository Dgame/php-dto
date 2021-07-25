<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Type;

final class MixedType extends Type implements Defaultable
{
    public function isAssignable(Type $other): bool
    {
        return true;
    }

    public function getDefaultValue(): mixed
    {
        return null;
    }

    public function isBuiltIn(): bool
    {
        return true;
    }

    public function __toString(): string
    {
        return 'mixed';
    }
}
