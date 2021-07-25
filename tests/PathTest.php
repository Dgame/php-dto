<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\PersonStub;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Throwable;

final class PathTest extends TestCase
{
    /**
     * @param array<string, mixed> $input
     * @param PersonStub $expected
     *
     * @throws ReflectionException
     * @throws Throwable
     *
     * @dataProvider providePersonData
     */
    public function testPath(array $input, PersonStub $expected): void
    {
        $stub = PersonStub::from($input);
        $this->assertEquals($expected, $stub);
    }

    /**
     * @return iterable<array>
     */
    public function providePersonData(): iterable
    {
        yield [
            ['id' => 42, 'person' => ['name' => 'Foo'], 'married' => ['$value' => true], 'first' => ['name' => ['#text' => 'Bar']]],
            new PersonStub(id: 42, name: 'Foo', married: true, firstname: 'Bar')
        ];

        yield [
            ['id' => 42, 'first' => ['name' => ['#text' => 'Bar']]],
            new PersonStub(id: 42, firstname: 'Bar')
        ];

        yield [
            ['id' => 42, 'person.name' => 'Foo', 'married' => ['$value' => true], 'first' => ['name' => ['#text' => 'Bar']]],
            new PersonStub(id: 42, married: true, firstname: 'Bar')
        ];

        yield [
            'married' => ['$value' => false],
            new PersonStub(married: false)
        ];

        $stub = new PersonStub();
        $stub->firstChild = ['born' => 'Junior', 'age' => 42];
        yield [
            ['child' => ['born' => 'Junior', 'age' => 42]],
            $stub
        ];

        $stub = new PersonStub();
        $stub->parent = new PersonStub(id: 23, name: 'Odin');
        yield [
            ['ancestor' => ['name' => 'Odin', 'married' => true, 'id' => 23]],
            $stub
        ];
    }
}
