<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\ValidationStrategy;
use Dgame\DataTransferObject\Annotation\Min;
use Dgame\DataTransferObject\DataTransfer;

#[ValidationStrategy(failFast: false)]
final class ValidationStrategyStub
{
    use DataTransfer;

    #[Min(3)]
    public string $name;
    #[Min(0)]
    public ?int $id;
}
