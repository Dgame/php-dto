<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;
use Throwable;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Ignore
{
    private ?Absent $absent = null;

    /**
     * @param class-string<Throwable>|null $exception
     * @param string|null $message
     */
    public function __construct(?string $exception = null, ?string $message = null)
    {
        if ($exception !== null || $message !== null) {
            $this->absent = new Absent(exception: $exception, message: $message);
        }
    }

    /**
     * @throws Throwable
     */
    public function execute(): void
    {
        $this->absent?->execute();
    }
}
