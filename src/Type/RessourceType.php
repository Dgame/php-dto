<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Type;

final class RessourceType extends Type
{
    public function isBuiltIn(): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return 'resource';
    }
}
