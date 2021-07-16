<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Annotation;

use Attribute;
use Dgame\DataTransferObject\Annotation\Validation;
use InvalidArgumentException;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class NumberBetween implements Validation
{
    public function __construct(private int|float $min, private int|float $max)
    {
    }

    public function validate(mixed $value): void
    {
        if (!is_numeric($value)) {
            throw new InvalidArgumentException(var_export($value, true) . ' must be a numeric value');
        }

        if ($value < $this->min) {
            throw new InvalidArgumentException(var_export($value, true) . ' must be >= ' . $this->min);
        }

        if ($value > $this->max) {
            throw new InvalidArgumentException(var_export($value, true) . ' must be <= ' . $this->max);
        }
    }
}
