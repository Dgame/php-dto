<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Type;

interface Defaultable
{
    public function getDefaultValue(): mixed;
}
