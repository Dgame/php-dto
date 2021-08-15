<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Required;
use Dgame\DataTransferObject\DataTransfer;

final class RequiredStub
{
    use DataTransfer;

    #[Required(reason: 'We need an "id" to identify the class')]
    public ?int $id;
}
