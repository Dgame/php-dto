<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\DenyUnknownFields;
use Dgame\DataTransferObject\From;

#[DenyUnknownFields]
final class DenyUnknownStub
{
    use From;

    private ?int $id;
}
