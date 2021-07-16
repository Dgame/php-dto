<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Absent;
use Dgame\DataTransferObject\From;

final class AbsentStub
{
    use From;

    #[Absent(message: 'We need an "id" to identify the class')]
    public int $id;
}
