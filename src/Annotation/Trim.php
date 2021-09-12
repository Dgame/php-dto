<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;
use ReflectionProperty;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Trim implements Transformation
{
    public function __construct(private string $characters = " \t\n\r\0\x0B")
    {
    }

    public function transform(mixed $value, ReflectionProperty $property): mixed
    {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = $this->transform($item, $property);
            }

            return $value;
        }

        return is_string($value) ? trim($value, $this->characters) : $value;
    }
}
