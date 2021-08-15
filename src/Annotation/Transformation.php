<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use ReflectionProperty;

interface Transformation
{
    public function transform(mixed $value, ReflectionProperty $property): mixed;
}
