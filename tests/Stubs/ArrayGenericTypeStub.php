<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Type;
use Dgame\DataTransferObject\DataTransfer;

final class ArrayGenericTypeStub
{
    use DataTransfer;

    /**
     * @param string[] $names
     */
    public function __construct(
        #[Type(name: 'string[]')] public array $names,
        #[Type(name: 'int[][]')] public array $ids
    )
    {
    }
}
