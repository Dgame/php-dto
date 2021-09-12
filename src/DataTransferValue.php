<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject;

use Dgame\DataTransferObject\Annotation\Transformation;
use Dgame\DataTransferObject\Annotation\Type;
use Dgame\DataTransferObject\Annotation\Validation;
use Dgame\DataTransferObject\Annotation\ValidationStrategy;
use ReflectionAttribute;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;
use Throwable;

final class DataTransferValue
{
    /**
     * @throws ReflectionException
     * @throws Throwable
     */
    public function __construct(private mixed $value, private ReflectionProperty $property, ValidationStrategy $validationStrategy)
    {
        $this->applyTransformations();
        $this->tryResolvingIntoObject();
        $this->validate($validationStrategy);
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    private function applyTransformations(): void
    {
        foreach ($this->property->getAttributes(Transformation::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            /** @var Transformation $transformation */
            $transformation = $attribute->newInstance();
            $this->value    = $transformation->transform($this->value, $this->property);
        }
    }

    /**
     * @throws ReflectionException
     * @throws Throwable
     */
    private function tryResolvingIntoObject(): void
    {
        if (!is_array($this->value)) {
            return;
        }

        $type = $this->property->getType();
        if ($type === null) {
            return;
        }

        if (!($type instanceof ReflectionNamedType)) {
            return;
        }

        if ($type->isBuiltin()) {
            return;
        }

        $typeName = $type->getName();
        if (!class_exists(class: $typeName, autoload: true)) {
            return;
        }

        $dto = new DataTransferObject($typeName);
        $dto->from($this->value);
        $this->value = $dto->getInstance();
    }

    private function validate(ValidationStrategy $validationStrategy): void
    {
        $typeChecked = false;
        foreach ($this->property->getAttributes(Validation::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            /** @var Validation $validation */
            $validation = $attribute->newInstance();
            $validation->validate($this->value, $validationStrategy);

            $typeChecked = $typeChecked || $validation instanceof Type;
        }

        if ($typeChecked || $validationStrategy->hasFailures()) {
            return;
        }

        $type = $this->property->getType();
        if ($type instanceof ReflectionNamedType) {
            Type::from($type)->validate($this->value, $validationStrategy);
        }
    }
}
