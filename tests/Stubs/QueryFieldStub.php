<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Min;
use Dgame\DataTransferObject\From;

final class QueryFieldStub
{
    use From;

    public function __construct(
        #[Min(1)] private string $query,
        private array $fields = []
    ) {
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}
