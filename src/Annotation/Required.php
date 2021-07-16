<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;
use InvalidArgumentException;
use Throwable;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Required
{
    private Exceptional $exceptional;

    /**
     * @param string $reason
     * @param class-string<Throwable> $exception
     */
    public function __construct(string $reason, string $exception = InvalidArgumentException::class)
    {
        $this->exceptional = new Exceptional(message: $reason, exception: $exception);
    }

    public function execute(): void
    {
        $this->exceptional->execute();
    }
}
