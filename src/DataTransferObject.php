<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject;

use Dgame\DataTransferObject\Annotation\Finalize;
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

    /**
     * @param class-string<T> $class
     *
     * @throws ReflectionException
     */
    public function __construct(string $class)
    {
        $this->reflection = new ReflectionClass($class);
        $this->constructor = $this->reflection->getConstructor();
        $this->createInstance();
    }

    /**
     * @param array<string, mixed> $input
     *
     * @throws Throwable
     */
    public function from(array &$input): void
    {
        foreach ($this->reflection->getProperties() as $property) {
            $dtp = new DataTransferProperty($property, $this);
            if ($dtp->isIgnored()) {
                $dtp->ignoreIn($input);
            } else {
                $dtp->setValueFrom($input);
            }
        }
    }

    /**
     * @param array<string, mixed> $input
     */
    public function finalize(array $input): void
    {
        foreach ($this->reflection->getAttributes(Finalize::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            /** @var Finalize $finalize */
            $finalize = $attribute->newInstance();
            $finalize->finalize($input);
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
}
