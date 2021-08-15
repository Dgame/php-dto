<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Alias;
use Dgame\DataTransferObject\Annotation\Name;
use Dgame\DataTransferObject\DataTransfer;

final class NameAliasMixStub
{
    use DataTransfer;

    #[Name('a')]
    #[Alias('z')]
    public int $id;

    #[Alias('x')]
    #[Alias('y')]
    public int $uuid;
}
