<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;
use ReflectionProperty;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Numeric implements Validation, Transformation
{
    public function __construct(private ?string $message = null)
    {
    }

    public function validate(mixed $value, ValidationStrategy $validationStrategy): void
    {
        if (!is_numeric($value)) {
            $validationStrategy->setFailure(
                strtr(
                    $this->message ?? 'Value {value} of {path} is not numeric',
                    ['{value}' => var_export($value, true)]
                )
            );
        }
    }

    public function transform(mixed $value, ReflectionProperty $property): mixed
    {
        $int = filter_var($value, filter: FILTER_VALIDATE_INT, options: FILTER_NULL_ON_FAILURE);
        if ($int !== null) {
            return $int;
        }

        $float = filter_var($value, filter: FILTER_VALIDATE_FLOAT, options: FILTER_NULL_ON_FAILURE);

        return $float ?? $value;
    }
}
