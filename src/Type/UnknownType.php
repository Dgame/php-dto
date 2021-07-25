<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Type;

final class UnknownType extends Type
{
    public function __construct(private string $name)
    {
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function isBuiltIn(): bool
    {
        return false;
    }
}
