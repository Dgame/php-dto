<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Name;
use Dgame\DataTransferObject\DataTransfer;

final class NestedStub
{
    use DataTransfer;

    public LimitStub $limit;
    #[Name('match')]
    public QueryFieldStub $query;
}
