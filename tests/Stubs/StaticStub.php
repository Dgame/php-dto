<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\DataTransfer;

final class StaticStub
{
    use DataTransfer;

    public static int $id = 0;
}
