<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Cast;
use Dgame\DataTransferObject\DataTransfer;

/**
 * Class FilterStub
 * @package Dgame\DataTransferObject\Tests\Stubs
 */
final class FilterStub
{
    use DataTransfer;

    #[Cast(method: 'toInt', class: FilterStubProvider::class)]
    private int $id;

    #[Cast(class: FilterStubProvider::class)]
    private array $filter = [];

    public function getId(): int
    {
        return $this->id;
    }

    public function getFilter(): array
    {
        return $this->filter;
    }
}
