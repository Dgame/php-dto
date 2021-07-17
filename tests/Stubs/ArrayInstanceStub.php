<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Instance;
use Dgame\DataTransferObject\From;

final class ArrayInstanceStub
{
    use From;

    #[Instance(class: LimitStub::class)]
    public array $limits;
}
