<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;
use Dgame\Type\Type as PhpType;
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

    public function validate(mixed $value, ValidationStrategy $failure): void
    {
        if ($value === null) {
            if (!$this->type->allowsNull()) {
                $failure->setFailure($this->message ?? 'Cannot assign null to non-nullable ' . $this->type->getName() . ' of {path}');
            }

            return;
        }

        $valueType = PhpType::fromValue($value);
        if (!$this->type->isAssignable($valueType)) {
            $failure->setFailure($this->message ?? 'Cannot assign ' . $valueType->getName() . ' ' . var_export($value, return: true) . ' to ' . $this->type->getName() . ' of {path}');
        }
    }
}
