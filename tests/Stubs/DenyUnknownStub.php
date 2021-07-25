<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\DenyUnknownFields;
use Dgame\DataTransferObject\DataTransfer;

#[DenyUnknownFields]
final class DenyUnknownStub
{
    use DataTransfer;

    private ?int $id;
}
