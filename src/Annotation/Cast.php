<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;
use Dgame\Type\Castable;
use Dgame\Type\Type as PhpType;
use Dgame\Type\UnionType;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Cast implements Transformation
{
    /**
     * @var PhpType[]
     */
    private array $types = [];

    /**
     * @param string[]          $types
     * @param class-string|null $class
     * @param string|null       $method
     *
     * @throws ReflectionException
     */
    public function __construct(array $types = [], private ?string $method = null, private ?string $class = null)
    {
        if ($this->class !== null) {
            $this->method ??= '__invoke';

            $refl = new ReflectionClass($this->class);
            if (!$refl->hasMethod($this->method)) {
                throw new InvalidArgumentException('Class ' . $this->class . ' needs to implement ' . $this->method);
            }
        }

        foreach ($types as $type) {
            $this->types[] = PhpType::fromName($type);
        }
    }

    public function transform(mixed $value, ReflectionProperty $property): mixed
    {
        $propertyType = $this->getType($property);
        if ($propertyType->accept($value)) {
            return $this->cast($value);
        }

        $this->validate($value);

        if (!($propertyType instanceof Castable) && (!($propertyType instanceof UnionType) || !$propertyType->isCastable())) {
            throw new InvalidArgumentException('Cannot cast to type ' . $propertyType->getName() . ' with value ' . var_export($value, true));
        }

        return $propertyType->cast($this->cast($value));
    }

    private function getType(ReflectionProperty $property): PhpType
    {
        $reflectionType = $property->getType();
        if ($reflectionType instanceof ReflectionNamedType) {
            return PhpType::fromReflection($reflectionType);
        }

        if ($reflectionType instanceof ReflectionUnionType) {
            $typeName = implode(
                '|',
                array_map(
                    static fn(ReflectionNamedType $type) => $type->getName(),
                    $reflectionType->getTypes()
                )
            );

            return PhpType::fromName($typeName);
        }

        throw new InvalidArgumentException('Cannot cast to unknown type');
    }

    private function validate(mixed $value): void
    {
        if ($this->types === []) {
            return;
        }

        $other = PhpType::fromValue($value);
        foreach ($this->types as $type) {
            if ($type instanceof $other) {
                return;
            }
        }

        throw new InvalidArgumentException(
            \Safe\sprintf(
                'Only %s %s accepted, %s (%s) given.',
                implode(' and ', array_map(static fn(PhpType $type) => $type->getName(), $this->types)),
                count($this->types) === 1 ? 'is' : 'are',
                $other->getName(),
                var_export($value, true)
            )
        );
    }

    private function cast(mixed $value): mixed
    {
        if ($this->method === null) {
            return $value;
        }

        if ($this->class === null) {
            if (!is_callable($this->method)) {
                throw new InvalidArgumentException('Need an callable, not ' . $this->method);
            }

            return ($this->method)($value);
        }

        $refl = new ReflectionClass($this->class);
        if (!$refl->hasMethod($this->method)) {
            throw new InvalidArgumentException('Class ' . $this->class . ' needs to implement ' . $this->method);
        }

        $method = $refl->getMethod($this->method);
        if ($method->isStatic()) {
            return $method->invoke(null, $value);
        }

        return $method->invoke($refl->newInstance(), $value);
    }
}
