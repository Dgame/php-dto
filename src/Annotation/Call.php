<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Call
{
    /**
     * @param class-string|null $class
     * @param string|null $method
     *
     * @throws ReflectionException
     */
    public function __construct(private ?string $class = null, private ?string $method = null)
    {
        if ($this->class !== null) {
            $this->method ??= '__invoke';

            $refl = new ReflectionClass($this->class);
            if (!$refl->hasMethod($this->method)) {
                throw new InvalidArgumentException('Class ' . $this->class . ' needs to implement ' . $this->method);
            }
        } elseif ($this->method === null) {
            throw new InvalidArgumentException('Need either a class method or a callable');
        }
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     * @throws ReflectionException
     */
    public function with(mixed $value): mixed
    {
        assert($this->method !== null);
        if ($this->class === null) {
            if (!is_callable($this->method)) {
                throw new InvalidArgumentException('Need an callable, not ' . $this->method);
            }

            return ($this->method)($value);
        }

        $refl = new ReflectionClass($this->class);
        if (!$refl->hasMethod($this->method)) {
            throw new InvalidArgumentException('Class ' . $this->class . ' needs to implement ' . $this->method);
        }

        $method = $refl->getMethod($this->method);
        if ($method->isStatic()) {
            return $method->invoke(null, $value);
        }

        return $method->invoke($refl->newInstance(), $value);
    }
}
