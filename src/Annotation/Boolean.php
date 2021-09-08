<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;
use ReflectionProperty;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Boolean implements Validation, Transformation
{
    public function __construct(private ?string $message = null)
    {
    }

    public function validate(mixed $value, ValidationStrategy $validationStrategy): void
    {
        $result = filter_var($value, filter: FILTER_VALIDATE_BOOL, options: FILTER_NULL_ON_FAILURE);
        if ($result === null) {
            $validationStrategy->setFailure(
                strtr(
                    $this->message ?? 'Value {value} of {path} is not a bool',
                    ['{value}' => var_export($value, true)]
                )
            );
        }
    }

    public function transform(mixed $value, ReflectionProperty $property): mixed
    {
        $result = filter_var($value, filter: FILTER_VALIDATE_BOOL, options: FILTER_NULL_ON_FAILURE);

        return $result ?? $value;
    }
}
