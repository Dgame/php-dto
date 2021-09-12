<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Matches;
use Dgame\DataTransferObject\Annotation\Trim;
use Dgame\DataTransferObject\DataTransfer;

final class MatchesStub
{
    use DataTransfer;

    #[Trim]
    #[Matches('/^[a-z_][a-z]{2,}/i')]
    public ?string $name = null;

    #[Matches('/^[1-9][0-9]$/')]
    public ?int $age = null;
}
