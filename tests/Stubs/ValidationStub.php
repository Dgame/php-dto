<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\DataTransfer;
use Dgame\DataTransferObject\Tests\Annotation\NumberBetween;

final class ValidationStub
{
    use DataTransfer;

    #[NumberBetween(18, 125)]
    private int $age;

    public function getAge(): int
    {
        return $this->age;
    }
}
