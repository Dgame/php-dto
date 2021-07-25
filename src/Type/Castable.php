<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Type;

interface Castable
{
    public function cast(mixed $value): mixed;
}
