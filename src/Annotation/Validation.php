<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Annotation;

interface Validation
{
    public function validate(mixed $value): void;
}
