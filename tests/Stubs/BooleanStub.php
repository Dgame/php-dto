<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Boolean;
use Dgame\DataTransferObject\DataTransfer;

final class BooleanStub
{
    use DataTransfer;

    #[Boolean]
    public ?bool $yes = null;
    #[Boolean]
    public ?bool $no = null;
    #[Boolean]
    public ?bool $on = null;
    #[Boolean]
    public ?bool $off = null;
    #[Boolean]
    public ?bool $one = null;
    #[Boolean]
    public ?bool $zero = null;
}
