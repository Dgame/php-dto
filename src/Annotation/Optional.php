<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;
use Dgame\DataTransferObject\Type\Defaultable;
use Dgame\DataTransferObject\Type\Type as PhpType;
use InvalidArgumentException;
use ReflectionNamedType;
use ReflectionProperty;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Optional
{
    public function __construct(private mixed $value = null)
    {
    }

    public function getValue(ReflectionProperty $property): mixed
    {
        $reflectionNamedType = $property->getType();
        if (!($reflectionNamedType instanceof ReflectionNamedType)) {
            throw new InvalidArgumentException('Cannot cast to unknown type');
        }

        $propertyType = PhpType::fromReflection($reflectionNamedType);

        return $this->value ?? ($propertyType instanceof Defaultable ? $propertyType->getDefaultValue() : $this->value);
    }
}
