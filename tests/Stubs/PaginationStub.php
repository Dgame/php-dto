<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Min;
use Dgame\DataTransferObject\From;

final class PaginationStub
{
    use From;

    #[Min(0)]
    private ?int $offset;
    #[Min(0)]
    private ?int $size;

    public function __construct(?int $from, ?int $size)
    {
        $this->offset = $from;
        $this->size = $size;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }
}
