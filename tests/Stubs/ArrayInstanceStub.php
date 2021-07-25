<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Instance;
use Dgame\DataTransferObject\DataTransfer;

final class ArrayInstanceStub
{
    use DataTransfer;

    #[Instance(class: LimitStub::class)]
    public array $limits;
}
