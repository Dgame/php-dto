<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject;

use Dgame\DataTransferObject\Annotation\Call;
use Dgame\DataTransferObject\Annotation\Validation;
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
    public function __construct(private mixed $value, private ReflectionProperty $property)
    {
        $this->applyCallbacks();
        $this->tryResolvingIntoObject();
        $this->validate();
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @throws ReflectionException
     */
    private function applyCallbacks(): void
    {
        foreach ($this->property->getAttributes(Call::class) as $attribute) {
            /** @var Call $call */
            $call        = $attribute->newInstance();
            $this->value = $call->with($this->value);
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

    private function validate(): void
    {
        foreach ($this->property->getAttributes(Validation::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            /** @var Validation $validation */
            $validation = $attribute->newInstance();
            $validation->validate($this->value);
        }
    }
}
