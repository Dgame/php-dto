<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Failure;

use Dgame\DataTransferObject\ValidationException;

class FailureHandler
{
    public function handle(FailureCollection $collection): void
    {
        if (!$collection->hasFailures()) {
            return;
        }

        throw new ValidationException(implode(PHP_EOL, $collection->getFailures()));
    }
}
