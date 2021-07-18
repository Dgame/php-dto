<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;
use Dgame\DataTransferObject\ValidationException;
use ReflectionNamedType;
use Safe\Exceptions\StringsException;
use SebastianBergmann\Type\NullType;
use SebastianBergmann\Type\Type as AbstractType;
use SebastianBergmann\Type\UnionType;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Type implements Validation
{
    private AbstractType $type;

    /**
     * @throws StringsException
     */
    public function __construct(string $name, bool $allowsNull = false, private ?string $message = null)
    {
        $name = trim($name);
        if (str_starts_with($name, '?')) {
            $allowsNull = true;
            $name       = \Safe\substr($name, start: 1);
        }

        $typeNames = array_map('trim', explode('|', $name));
        if ($typeNames !== [$name]) {
            $typeNames = array_map(
                static fn(string $typeName) => AbstractType::fromName($typeName, allowsNull: false),
                $typeNames
            );
            if ($allowsNull) {
                $typeNames[] = new NullType();
            }
            $this->type = new UnionType(...$typeNames);
        } else {
            $this->type = AbstractType::fromName($name, allowsNull: $allowsNull);
        }
    }

    /**
     * @throws StringsException
     */
    public static function from(ReflectionNamedType $type): self
    {
        return new self($type->getName(), allowsNull: $type->allowsNull());
    }

    public function validate(mixed $value): void
    {
        if ($value === null) {
            if (!$this->type->allowsNull()) {
                throw new ValidationException($this->message ?? 'Cannot assign null to non-nullable ' . $this->type->name());
            }

            return;
        }

        $valueType = AbstractType::fromValue($value, allowsNull: false);
        if (!$this->type->isAssignable($valueType)) {
            throw new ValidationException($this->message ?? 'Cannot assign ' . $valueType->name() . ' ' . var_export($value, true) . ' to ' . $this->type->name());
        }
    }
}
