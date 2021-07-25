<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Type;

use Closure;
use ReflectionClass;

class ObjectType extends Type implements Defaultable
{
    public function __construct(private string $name)
    {
    }

    public function isAssignable(Type $other): bool
    {
        if ($other instanceof $this) {
            if ($this->getFullQualifiedName() === $other->getFullQualifiedName()) {
                return true;
            }

            if (is_subclass_of($other->getFullQualifiedName(), $this->getFullQualifiedName(), allow_string: true)) {
                return true;
            }
        }

        return false;
    }

    public function isInvokable(): bool
    {
        if ($this->isClosure()) {
            return true;
        }

        /** @phpstan-ignore-next-line */
        $refl = new ReflectionClass($this->name);

        return $refl->hasMethod('__invoke');
    }

    public function isClosure(): bool
    {
        return str_ends_with($this->name, Closure::class);
    }

    public function getDefaultValue(): mixed
    {
        return null;
    }

    public function isBuiltIn(): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getFullQualifiedName(): string
    {
        return $this->name;
    }
}
