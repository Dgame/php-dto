<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Matches implements Validation
{
    public function __construct(private string $pattern, private ?string $message = null)
    {
    }

    public function validate(mixed $value, ValidationStrategy $validationStrategy): void
    {
        if (is_scalar($value) && \Safe\preg_match($this->pattern, (string) $value) !== 1) {
            $validationStrategy->setFailure(
                strtr(
                    $this->message ?? '{value} of {path} does not match pattern {pattern}',
                    [
                        '{value}' => var_export($value, true),
                        '{values}' => $this->pattern
                    ]
                )
            );
        }
    }
}
