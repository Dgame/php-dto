<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;
use Dgame\Type\Castable;
use Dgame\Type\Type as PhpType;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Cast implements Transformation
{
    /**
     * @var PhpType[]
     */
    private array $types = [];

    /**
     * @param string[] $types
     * @param class-string|null $class
     * @param string|null $method
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
        $reflectionNamedType = $property->getType();
        if (!($reflectionNamedType instanceof ReflectionNamedType)) {
            throw new InvalidArgumentException('Cannot cast to unknown type');
        }

        $propertyType = PhpType::fromReflection($reflectionNamedType);
        if (!($propertyType instanceof Castable)) {
            throw new InvalidArgumentException('Cannot cast to type ' . $reflectionNamedType->getName());
        }

        if (!$this->accepts($value)) {
            throw new InvalidArgumentException($propertyType->getName() . ' is not accepted');
        }

        return $propertyType->cast($this->cast($value));
    }

    private function accepts(mixed $value): bool
    {
        if ($this->types === []) {
            return true;
        }

        foreach ($this->types as $type) {
            if ($type->accept($value)) {
                return true;
            }
        }

        return false;
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
