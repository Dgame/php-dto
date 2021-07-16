<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\QueryFieldStub;
use PHPUnit\Framework\TestCase;

final class QueryFieldTest extends TestCase
{
    /**
     * @param array<string, mixed> $input
     * @param string               $expectedQuery
     * @param string[]             $expectedFields
     *
     * @dataProvider provideQueryFieldInput
     */
    public function testQueryFieldStub(array $input, string $expectedQuery, array $expectedFields): void
    {
        $stub = QueryFieldStub::from($input);
        $this->assertEquals($expectedQuery, $stub->getQuery());
        $this->assertEquals($expectedFields, $stub->getFields());
    }

    public function provideQueryFieldInput(): iterable
    {
        yield 'Just Query' => [
            ['query' => 'abc | def'],
            'abc | def',
            []
        ];

        yield 'Query char' => [
            ['query' => '*'],
            '*',
            []
        ];

        yield 'With Fields' => [
            ['query' => 'a+b', 'fields' => ['x', 'y', 'z']],
            'a+b',
            ['x', 'y', 'z']
        ];
    }
}
