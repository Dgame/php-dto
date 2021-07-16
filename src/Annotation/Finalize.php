<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

interface Finalize
{
    /**
     * @param array<string, mixed> $input
     */
    public function finalize(array $input): void;
}
