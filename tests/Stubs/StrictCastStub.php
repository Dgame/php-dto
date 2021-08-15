<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Cast;
use Dgame\DataTransferObject\DataTransfer;

final class StrictCastStub
{
    use DataTransfer;

    #[Cast(types: ['float'])]
    public ?int $age;

    public function __construct(?int $age = null)
    {
        $this->age = $age;
    }
}
