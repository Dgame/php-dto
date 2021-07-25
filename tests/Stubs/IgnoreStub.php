<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Ignore;
use Dgame\DataTransferObject\DataTransfer;

final class IgnoreStub
{
    use DataTransfer;

    #[Ignore]
    public string $uuid = 'abc';
    public int $id = 0;
}
