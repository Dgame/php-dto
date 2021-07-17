<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Call;
use Dgame\DataTransferObject\From;

final class CastStub
{
    use From;

    #[Call(class: self::class, method: 'toInt')]
    public int $id;

    public static function toInt(string|int|float|bool $value): int
    {
        return (int) $value;
    }
}
