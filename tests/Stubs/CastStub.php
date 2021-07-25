<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Cast;
use Dgame\DataTransferObject\DataTransfer;

final class CastStub
{
    use DataTransfer;

    #[Cast(method: 'toInt', class: self::class)]
    public int $id;

    public static function toInt(string|int|float|bool $value): int
    {
        return (int) $value;
    }
}
