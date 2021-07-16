<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Name;
use Dgame\DataTransferObject\From;

final class NestedStub
{
    use From;

    public LimitStub $limit;
    #[Name('match')]
    public QueryFieldStub $query;
}
