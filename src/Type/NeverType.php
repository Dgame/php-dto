<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Type;

final class NeverType extends Type
{
    public function isBuiltIn(): bool
    {
        return true;
    }

    public function __toString(): string
    {
        return 'never';
    }
}
