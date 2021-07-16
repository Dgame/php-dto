<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\UnionTypeStub;
use PHPUnit\Framework\TestCase;

final class UnionTypeTest extends TestCase
{
    /**
     * @param array      $input
     * @param int|string $expected
     *
     * @dataProvider provideInput
     */
    public function testUnionType(array $input, int|string $expected): void
    {
        $ut = UnionTypeStub::from($input);
        $this->assertEquals($expected, $ut->getId());
    }

    public function provideInput(): iterable
    {
        yield [['id' => 42], 42];
        yield [['id' => 'abc'], 'abc'];
    }
}
