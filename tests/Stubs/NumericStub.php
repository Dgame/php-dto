<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Numeric;
use Dgame\DataTransferObject\DataTransfer;

final class NumericStub
{
    use DataTransfer;

    #[Numeric]
    public ?int $int = null;
    #[Numeric]
    public ?float $float = null;
}
