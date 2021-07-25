<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\SelfValidation;
use Dgame\DataTransferObject\DataTransfer;

#[SelfValidation(method: 'validate')]
final class SelfValidationStub
{
    use DataTransfer;

    public function __construct(public int $id)
    {
    }

    public function validate(): void
    {
        assert($this->id > 0);
    }
}
