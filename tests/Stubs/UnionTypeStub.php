<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Min;
use Dgame\DataTransferObject\DataTransfer;

final class UnionTypeStub
{
    use DataTransfer;

    public function __construct(#[Min(3)] private int|string $id)
    {
    }

    public function getId(): int|string
    {
        return $this->id;
    }
}
