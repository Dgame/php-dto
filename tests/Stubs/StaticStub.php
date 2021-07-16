<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\From;

final class StaticStub
{
    use From;

    public static int $id = 0;
}
