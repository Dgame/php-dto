<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Type;

final class FalseType extends Type implements Defaultable
{
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
        return 'false';
    }
}
