<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Instance implements Validation
{
    public function __construct(private string $class, private ?string $message = null)
    {
    }

    public function validate(mixed $value, ValidationStrategy $validationStrategy): void
    {
        if (is_array($value)) {
            foreach ($value as $item) {
                $this->validate($item, $validationStrategy);
            }

            return;
        }

        if (!is_object($value)) {
            $validationStrategy->setFailure($this->message ?? var_export($value, true) . ' must be an object-instance of ' . $this->class . ' in {path}');

            return;
        }

        if (!($value instanceof $this->class)) {
            $validationStrategy->setFailure($this->message ?? var_export($value, true) . ' is not an instance of ' . $this->class . ' in {path}');
        }
    }
}
