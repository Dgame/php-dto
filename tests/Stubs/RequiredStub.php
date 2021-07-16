<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Required;
use Dgame\DataTransferObject\From;

final class RequiredStub
{
    use From;

    #[Required(reason: 'We need an "id" to identify the class')]
    public ?int $id;
}
