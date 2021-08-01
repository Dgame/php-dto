<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

use Attribute;
use Dgame\DataTransferObject\Failure\FailureCollection;
use Dgame\DataTransferObject\Failure\FailureHandler;

#[Attribute(flags: Attribute::TARGET_CLASS)]
class ValidationStrategy
{
    private FailureCollection $collection;
    private FailureHandler $handler;

    public function __construct(?FailureCollection $collection = null, ?FailureHandler $handler = null, private bool $failFast = true)
    {
        $this->collection = $collection ?? new FailureCollection();
        $this->handler = $handler ?? new FailureHandler();
    }

    public function pushPath(string $path): void
    {
        $this->collection->pushPath($path);
    }

    public function popPath(): ?string
    {
        return $this->collection->popPath();
    }

    public function setFailure(string $failure): void
    {
        $this->collection->setFailure($failure);
        if ($this->failFast) {
            $this->handle();
        }
    }

    public function handle(): void
    {
        $this->handler->handle($this->collection);
    }
}
