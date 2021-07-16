<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Ignore;
use Dgame\DataTransferObject\Annotation\Reject;
use Dgame\DataTransferObject\From;

final class RejectStub
{
    use From;

    #[Ignore]
    #[Reject(reason: 'The attribute "uuid" is not supposed to be set')]
    public string $uuid = 'abc';
    #[Reject(reason: 'The attribute "new" is not supposed to be set')]
    public bool $new = true;
    public int $id = 0;
}
