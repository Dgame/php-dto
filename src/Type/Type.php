<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Type;

use InvalidArgumentException;
use ReflectionNamedType;
use Stringable;

abstract class Type implements Stringable
{
    public static function fromReflection(ReflectionNamedType $type): self
    {
        return self::fromName($type->getName(), allowsNull: $type->allowsNull());
    }

    public static function fromValue(mixed $value): self
    {
        return self::fromName(is_object($value) ? get_class($value) : gettype($value));
    }

    public static function fromName(string $name, bool $allowsNull = false): self
    {
        $name = trim($name);
        if (str_starts_with($name, '?')) {
            $allowsNull = true;
            $name       = \Safe\substr($name, 1);
        }

        if (str_contains($name, '|')) {
            $names = array_map(static fn(string $name) => trim($name), explode('|', $name));

            return new UnionType(...array_map(static fn(string $name) => self::identify($name), $names));
        }

        if (str_contains($name, '&')) {
            $names = array_map(static fn(string $name) => trim($name), explode('&', $name));

            return new IntersectionType(...array_map(static fn(string $name) => self::identify($name), $names));
        }

        return self::identify($name, $allowsNull);
    }

    private static function identify(string $name, bool $allowsNull = false): self
    {
        return match (strtolower($name)) {
            'callable' => $allowsNull ? new UnionType(new CallableType(), new NullType()) : new CallableType(),
            'false' => $allowsNull ? new UnionType(new FalseType(), new NullType()) : new FalseType(),
            'iterable' => $allowsNull ? new UnionType(new IterableType(), new NullType()) : new IterableType(),
            'null' => $allowsNull ? throw new InvalidArgumentException('null is already null') : new NullType(),
            'mixed' => $allowsNull ? throw new InvalidArgumentException('mixed is nullable') : new MixedType(),
            'resource', 'resource (closed)' => $allowsNull ? new UnionType(new RessourceType(), new NullType()) : new RessourceType(),
            'array' => $allowsNull ? new UnionType(new ArrayType(), new NullType()) : new ArrayType(),
            'bool', 'boolean' => $allowsNull ? new UnionType(new BoolType(), new NullType()) : new BoolType(),
            'double', 'float', 'real' => $allowsNull ? new UnionType(new FloatType(), new NullType()) : new FloatType(),
            'int', 'integer' => $allowsNull ? new UnionType(new IntType(), new NullType()) : new IntType(),
            'string' => $allowsNull ? new UnionType(new StringType(), new NullType()) : new StringType(),
            'object' => $allowsNull ? new UnionType(new ObjectType($name), new NullType()) : new ObjectType($name),
            'static' => $allowsNull ? new UnionType(new StaticType(), new NullType()) : new StaticType(),
            'parent' => $allowsNull ? new UnionType(new ParentType(), new NullType()) : new ParentType(),
            'self' => $allowsNull ? new UnionType(new SelfType(), new NullType()) : new SelfType(),
            'never' => new NeverType(),
            default => self::identifyUnknown($name, $allowsNull)
        };
    }

    private static function identifyUnknown(string $name, bool $allowsNull): self
    {
        if (class_exists(class: $name, autoload: true)) {
            return $allowsNull ? new UnionType(new ObjectType($name), new NullType()) : new ObjectType($name);
        }

        return $allowsNull ? new UnionType(new UnknownType($name), new NullType()) : new UnknownType($name);
    }

    public function getName(): string
    {
        return (string) $this;
    }

    final public function is(self $type): bool
    {
        return $type instanceof $this;
    }

    public function isAssignable(self $other): bool
    {
        return $this->is($other);
    }

    final public function accept(mixed $value): bool
    {
        return $this->isAssignable(self::fromValue($value));
    }

    public function allowsNull(): bool
    {
        return false;
    }

    abstract public function isBuiltIn(): bool;
}
