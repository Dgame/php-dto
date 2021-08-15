<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Annotation;

use Attribute;
use Dgame\DataTransferObject\Annotation\ValidationStrategy;
use Dgame\DataTransferObject\Annotation\Validation;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class NumberBetween implements Validation
{
    public function __construct(private int|float $min, private int|float $max)
    {
    }

    public function validate(mixed $value, ValidationStrategy $validationStrategy): void
    {
        if (!is_numeric($value)) {
            $validationStrategy->setFailure(var_export($value, true) . ' must be a numeric value');

            return;
        }

        if ($value < $this->min) {
            $validationStrategy->setFailure(var_export($value, true) . ' must be >= ' . $this->min);
        }

        if ($value > $this->max) {
            $validationStrategy->setFailure(var_export($value, true) . ' must be <= ' . $this->max);
        }
    }
}
