<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Ignore;
use Dgame\DataTransferObject\From;

final class IgnoreWithExceptionStub
{
    use From;

    #[Ignore(message: 'The uuid is not supposed to be set')]
    public string $uuid = 'abc';
    public int $id = 0;
}
