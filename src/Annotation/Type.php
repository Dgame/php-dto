<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;
use Dgame\DataTransferObject\Type\Type as PhpType;
use Dgame\DataTransferObject\ValidationException;
use ReflectionNamedType;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Type implements Validation
{
    private PhpType $type;

    public function __construct(string $name, bool $allowsNull = false, private ?string $message = null)
    {
        $this->type = PhpType::fromName($name, $allowsNull);
    }

    public static function from(ReflectionNamedType $type): self
    {
        return new self($type->getName(), allowsNull: $type->allowsNull());
    }

    public function validate(mixed $value): void
    {
        if ($value === null) {
            if (!$this->type->allowsNull()) {
                throw new ValidationException($this->message ?? 'Cannot assign null to non-nullable ' . $this->type->getName());
            }

            return;
        }

        $valueType = PhpType::fromValue($value);
        if (!$this->type->isAssignable($valueType)) {
            throw new ValidationException($this->message ?? 'Cannot assign ' . $valueType->getName() . ' ' . var_export($value, true) . ' to ' . $this->type->getName());
        }
    }
}
