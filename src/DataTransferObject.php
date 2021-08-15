<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject;

use Dgame\DataTransferObject\Annotation\Finalize;
use Dgame\DataTransferObject\Annotation\SelfValidation;
use Dgame\DataTransferObject\Annotation\ValidationStrategy;
use Dgame\DataTransferObject\Failure\FailureCollection;
use Dgame\DataTransferObject\Failure\FailureHandler;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Throwable;

/**
 * @template T of object
 */
final class DataTransferObject
{
    /**
     * @var ReflectionClass<T>
     */
    private ReflectionClass $reflection;
    /**
     * @var T
     */
    private object $object;
    private ?ReflectionMethod $constructor;
    private ValidationStrategy $validationStrategy;

    /**
     * @param class-string<T> $class
     *
     * @throws ReflectionException
     */
    public function __construct(string $class)
    {
        $this->reflection  = new ReflectionClass($class);
        $this->constructor = $this->reflection->getConstructor();
        $this->createInstance();
        $this->createValidationStrategy();
    }

    /**
     * @param array<string, mixed> $input
     *
     * @throws Throwable
     */
    public function from(array &$input): void
    {
        $this->validationStrategy->pushPath($this->reflection->getShortName());

        foreach ($this->reflection->getProperties() as $property) {
            $this->validationStrategy->pushPath($property->getName());

            $dtp = new DataTransferProperty($property, $this);
            if ($dtp->isIgnored()) {
                $dtp->ignoreIn($input);
            } else {
                $dtp->setValueFrom($input);
            }

            $this->validationStrategy->popPath();
        }

        $this->validationStrategy->popPath();
    }

    /**
     * @param array<string, mixed> $input
     *
     * @throws ReflectionException
     */
    public function finalize(array $input): void
    {
        $this->validationStrategy->handle();

        foreach ($this->reflection->getAttributes(Finalize::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            /** @var Finalize $finalize */
            $finalize = $attribute->newInstance();
            $finalize->finalize($input);
        }

        foreach ($this->reflection->getAttributes(SelfValidation::class) as $attribute) {
            /** @var SelfValidation $validation */
            $validation = $attribute->newInstance();
            $method     = $this->reflection->getMethod($validation->getMethod());
            $method->invoke($this->object);
        }
    }

    /**
     * @return T
     */
    public function getInstance(): object
    {
        return $this->object;
    }

    public function getConstructor(): ?ReflectionMethod
    {
        return $this->constructor;
    }

    public function getValidationStrategy(): ValidationStrategy
    {
        return $this->validationStrategy;
    }

    /**
     * @throws ReflectionException
     */
    private function createInstance(): void
    {
        if ($this->constructor === null || $this->constructor->getNumberOfRequiredParameters() === 0) {
            $this->object = $this->reflection->newInstance();
        } else {
            $this->object = $this->reflection->newInstanceWithoutConstructor();
        }
    }

    private function createValidationStrategy(): void
    {
        foreach ($this->reflection->getAttributes(ValidationStrategy::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            /** @var ValidationStrategy $validationStrategy */
            $validationStrategy       = $attribute->newInstance();
            $this->validationStrategy = $validationStrategy;
            break;
        }

        $this->validationStrategy ??= new ValidationStrategy(
            collection: new FailureCollection(),
            handler:    new FailureHandler()
        );
    }
}
