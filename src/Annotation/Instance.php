<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;
use InvalidArgumentException;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Instance implements Validation
{
    public function __construct(private string $class, private ?string $message = null)
    {
    }

    public function validate(mixed $value): void
    {
        if (is_array($value)) {
            foreach ($value as $item) {
                $this->validate($item);
            }

            return;
        }

        if (!is_object($value)) {
            throw new InvalidArgumentException($this->message ?? var_export($value, true) . ' must be an object-instance of ' . $this->class);
        }

        if (!($value instanceof $this->class)) {
            throw new InvalidArgumentException($this->message ?? var_export($value, true) . ' is not an instance of ' . $this->class);
        }
    }
}
