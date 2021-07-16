<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\FilterStub;
use PHPUnit\Framework\TestCase;

final class FilterTest extends TestCase
{
    /**
     * @param array $input
     * @param array $expectedFilter
     * @param int   $expectedId
     *
     * @dataProvider provideData
     */
    public function testFilter(array $input, array $expectedFilter, int $expectedId): void
    {
        $filter = FilterStub::from($input);
        $this->assertEquals($expectedId, $filter->getId());
        $this->assertEquals($expectedFilter, $filter->getFilter());
    }

    public function provideData(): iterable
    {
        yield [
            ['filter' => [1, 2, 3], 'id' => '_'],
            [0 => base64_encode('1'), 1 => base64_encode('2'), 2 => base64_encode('3')],
            0
        ];

        yield [
            ['filter' => ['a' => 'b', 'x' => 'y'], 'id' => '42a'],
            ['a' => base64_encode('b'), 'x' => base64_encode('y')],
            42
        ];
    }
}
