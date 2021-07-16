<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;
use InvalidArgumentException;
use Throwable;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Absent
{
    /**
     * @param class-string<Throwable>|null $exception
     * @param string|null $message
     */
    public function __construct(private ?string $exception = null, private ?string $message = null)
    {
        if ($this->exception === null && $this->message === null) {
            throw new InvalidArgumentException('Either $exception or $message must be set');
        }
    }

    public function execute(): void
    {
        throw $this->getException();
    }

    public function getException(): Throwable
    {
        if ($this->exception !== null) {
            $exception = new $this->exception();
            /** @phpstan-ignore-next-line */
            assert($exception instanceof Throwable);

            return $exception;
        }

        assert($this->message !== null);

        return new InvalidArgumentException($this->message);
    }
}
