<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Max implements Validation
{
    public function __construct(private int|float $maxValue, private ?string $message = null)
    {
    }

    public function validate(mixed $value, ValidationStrategy $failure): void
    {
        if ((is_int($value) || is_float($value))) {
            if ($value > $this->maxValue) {
                $failure->setFailure($this->message ?? 'Value ' . var_export($value, true) . ' of {path} must be <= ' . $this->maxValue);
            }

            return;
        }

        if (is_string($value)) {
            if (strlen($value) > $this->maxValue) {
                $failure->setFailure($this->message ?? 'Value ' . var_export($value, true) . ' of {path} must have at most a length of ' . $this->maxValue);
            }

            return;
        }

        if (is_array($value) && count($value) > $this->maxValue) {
            $failure->setFailure($this->message ?? 'Value ' . var_export($value, true) . ' of {path} must have at most a length of ' . $this->maxValue);
        }
    }
}
