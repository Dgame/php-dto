<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\ArrayGenericTypeStub;
use Dgame\DataTransferObject\ValidationException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Throwable;

final class ArrayTypeTest extends TestCase
{
    /**
     * @param array                $input
     * @param ArrayGenericTypeStub $expected
     *
     * @throws ReflectionException
     * @throws Throwable
     *
     * @dataProvider provideArrayTypeData
     */
    public function testArrayType(array $input, ArrayGenericTypeStub $expected): void
    {
        $stub = ArrayGenericTypeStub::from($input);
        $this->assertEquals($expected, $stub);
    }

    /**
     * @param array  $input
     * @param string|null $expectedMessage
     *
     * @throws ReflectionException
     * @throws Throwable
     * @dataProvider provideArrayWrongTypeData
     */
    public function testArrayWrongType(array $input, string $expectedMessage = null): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessageMatches($expectedMessage);

        ArrayGenericTypeStub::from($input);
    }

    public function provideArrayTypeData(): iterable
    {
        yield [['names' => [], 'ids' => [[1]]], new ArrayGenericTypeStub(names: [], ids: [[1]])];
        yield [['names' => [''], 'ids' => [[1, 2]]], new ArrayGenericTypeStub(names: [''], ids: [[1, 2]])];
        yield [['names' => ['foo'], 'ids' => [[1, 2]]], new ArrayGenericTypeStub(names: ['foo'], ids: [[1, 2]])];
        yield [['names' => ['foo', 'bar'], 'ids' => [[1, 2]]], new ArrayGenericTypeStub(names: ['foo', 'bar'], ids: [[1, 2]])];
        yield [['names' => ['a', 'b', 'c'], 'ids' => [[1, 2]]], new ArrayGenericTypeStub(names: ['a', 'b', 'c'], ids: [[1, 2]])];
    }

    public function provideArrayWrongTypeData(): iterable
    {
        yield [['names' => null], '/Cannot assign null to non-nullable array<int, string>/'];
        yield [['names' => [1]], '/Cannot assign array<int, int> array \(.+?\) to array<int, string>/s'];
        yield [['names' => [1, 2]], '/Cannot assign array<int, int> array \(.+?\) to array<int, string>/s'];
        yield [['names' => [1, 2, 3]], '/Cannot assign array<int, int> array \(.+?\) to array<int, string>/s'];
        yield [['names' => [], 'ids' => [1]], '/Cannot assign array<int, int> array \(.+?\) to array<int, array<int, int>>/s'];
    }
}
