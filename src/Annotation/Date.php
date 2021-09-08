<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;
use DateTime;
use DateTimeZone;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Date implements Validation
{
    public function __construct(private ?string $format = null, private ?int $timezone = null, private ?string $message = null)
    {
    }

    public function validate(mixed $value, ValidationStrategy $validationStrategy): void
    {
        $dt = null;
        if ($this->format !== null) {
            /** @phpstan-ignore-next-line => short ternary */
            $dt = DateTime::createFromFormat($this->format, $value) ?: null;
        } else {
            /** @phpstan-ignore-next-line => short ternary */
            $info = date_parse($value) ?: [];
            if (($info['error_count'] ?? 0) === 0 && ($info['warning_count'] ?? 0) === 0) {
                /** @phpstan-ignore-next-line */
                $dt = new DateTime($value, $this->timezone === null ? null : new DateTimeZone($this->timezone));
            }
        }

        if ($dt === null) {
            $validationStrategy->setFailure(
                strtr(
                    $this->message ?? '{value} of {path} is not a Date',
                    ['{value}' => var_export($value, true)]
                )
            );
        }
    }
}
