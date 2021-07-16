<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use InvalidArgumentException;
use Throwable;

final class Exceptional
{
    /**
     * @param string                  $message
     * @param class-string<Throwable> $exception
     */
    public function __construct(private string $message, private string $exception = InvalidArgumentException::class)
    {
    }

    public function execute(): void
    {
        throw $this->getException();
    }

    private function getException(): Throwable
    {
        $exception = new ($this->exception)($this->message);
        /** @phpstan-ignore-next-line */
        assert($exception instanceof Throwable);

        return $exception;
    }
}
