<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Call;
use Dgame\DataTransferObject\From;

/**
 * Class FilterStub
 * @package Dgame\DataTransferObject\Tests\Stubs
 */
final class FilterStub
{
    use From;

    #[Call(class: FilterStubProvider::class, method: 'toInt')]
    private int $id;

    #[Call(class: FilterStubProvider::class)]
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
