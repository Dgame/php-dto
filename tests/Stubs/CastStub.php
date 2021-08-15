<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests\Stubs;

use Dgame\DataTransferObject\Annotation\Cast;
use Dgame\DataTransferObject\DataTransfer;

final class CastStub
{
    use DataTransfer;

    #[Cast]
    public int|float|null $uid;

    #[Cast(method: 'toInt', class: self::class)]
    public ?int $id;

    #[Cast(types: ['string', 'float', 'bool'])]
    public ?int $age;

    public function __construct(?int $id = null, ?int $age = null, int|float|null $uid = null)
    {
        $this->id  = $id;
        $this->age = $age;
        $this->uid = $uid;
    }

    public static function toInt(string|int|float|bool $value): int
    {
        return (int) $value;
    }
}
