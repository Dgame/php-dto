<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Optional;
use Dgame\DataTransferObject\DataTransfer;

final class OptionalStub
{
    use DataTransfer;

    public function __construct(
        #[Optional]
        public int $id,
        #[Optional(value: 42)]
        public int $answer,
        #[Optional]
        public ?string $question,
        #[Optional(value: 'foobar')]
        public ?string $message,
        #[Optional(value: 99)]
        public int $age = 18)
    {
    }
}
