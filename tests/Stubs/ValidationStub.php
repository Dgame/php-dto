<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\From;
use Dgame\DataTransferObject\Tests\Annotation\NumberBetween;

final class ValidationStub
{
    use From;

    #[NumberBetween(18, 125)]
    private int $age;

    public function getAge(): int
    {
        return $this->age;
    }
}
