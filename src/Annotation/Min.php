<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;
use InvalidArgumentException;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Min implements Validation
{
    public function __construct(private int|float $minValue, private ?string $message = null)
    {
    }

    public function validate(mixed $value): void
    {
        if ((is_int($value) || is_float($value))) {
            if ($value < $this->minValue) {
                throw new InvalidArgumentException($this->message ?? var_export($value, true) . ' is < ' . $this->minValue);
            }

            return;
        }

        if (is_string($value)) {
            if (strlen($value) < $this->minValue) {
                throw new InvalidArgumentException($this->message ?? var_export($value, true) . ' is < ' . $this->minValue);
            }

            return;
        }

        if (is_array($value) && count($value) < $this->minValue) {
            throw new InvalidArgumentException($this->message ?? var_export($value, true) . ' is < ' . $this->minValue);
        }
    }
}
