<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Max;
use Dgame\DataTransferObject\Annotation\Min;
use Dgame\DataTransferObject\Annotation\Name;
use Dgame\DataTransferObject\DataTransfer;

final class LimitStub
{
    use DataTransfer;

    public function __construct(
        #[Min(0, message: 'offset must be at least 0'),
        Max(1000),
        Name('offset')] private ?int $from,
        #[Min(0),
        Max(1000)] private ?int $size
    ) {
    }

    public function getFrom(): ?int
    {
        return $this->from;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }
}
