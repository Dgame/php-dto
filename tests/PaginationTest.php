<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\PaginationStub;
use PHPUnit\Framework\TestCase;

final class PaginationTest extends TestCase
{
    /**
     * @param array<string, mixed> $input
     * @param int|null             $expectedOffset
     * @param int|null             $expectedSize
     *
     * @dataProvider provideLimitInput
     */
    public function testPaginationStub(array $input, ?int $expectedOffset, ?int $expectedSize): void
    {
        $stub = PaginationStub::from($input);
        $this->assertEquals($expectedOffset, $stub->getOffset());
        $this->assertEquals($expectedSize, $stub->getSize());
    }

    public function provideLimitInput(): iterable
    {
        yield 'Empty' => [
            [],
            null,
           null
        ];

        yield 'Offset 0' => [
            ['offset' => 0],
            0,
            null
        ];

        yield 'Size 42' => [
            ['size' => 42],
            null,
            42
        ];

        yield 'Offset 23, Size 42' => [
            ['offset' => 23, 'size' => 42],
            23,
            42
        ];
    }
}
