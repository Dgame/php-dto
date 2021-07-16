<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\StaticStub;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Throwable;

final class StaticTest extends TestCase
{
    /**
     * @param array $input
     * @param int   $expectedId
     *
     * @throws ReflectionException
     * @throws Throwable
     *
     * @dataProvider provideStaticData
     */
    public function testStatic(array $input, int $expectedId): void
    {
        $stub = StaticStub::from($input);
        $this->assertEquals($expectedId, $stub::$id);
        $this->assertEquals($expectedId, StaticStub::$id);
    }

    public function provideStaticData(): iterable
    {
        yield [[], 0];
        yield [['id' => 42], 42];
        yield [['id' => 23], 23];
        yield [[], 23];
    }
}
