<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Ignore;
use Dgame\DataTransferObject\From;

final class IgnoreStub
{
    use From;

    #[Ignore]
    public string $uuid = 'abc';
    public int $id = 0;
}
