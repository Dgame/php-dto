<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\LimitStub;
use Dgame\DataTransferObject\ValidationException;
use PHPUnit\Framework\TestCase;

final class LimitTest extends TestCase
{
    /**
     * @param array<string, mixed> $input
     * @param int|null             $expectedOffset
     * @param string|int|null      $expectedSize
     *
     * @throws \ReflectionException
     * @throws \Throwable
     * @dataProvider provideLimitInput
     */
    public function testLimitStub(array $input, ?int $expectedOffset, string|int|null $expectedSize): void
    {
        if (is_string($expectedSize)) {
            $this->expectException(ValidationException::class);
            $this->expectExceptionMessage('Cannot assign string \'' . $expectedSize . '\' to int');
        }

        $stub = LimitStub::from($input);
        $this->assertEquals($expectedOffset, $stub->getFrom());
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

        yield 'Size is string' => [
            ['size' => 'a'],
            null,
            'a'
        ];

        yield 'Offset 23, Size 42' => [
            ['offset' => 23, 'size' => 42],
            23,
            42
        ];
    }
}
