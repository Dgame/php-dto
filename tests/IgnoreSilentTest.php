<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\IgnoreSilentStub;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Throwable;

final class IgnoreSilentTest extends TestCase
{
    /**
     * @param array $input
     * @param int   $expectedId
     *
     * @throws ReflectionException
     * @throws Throwable
     *
     * @dataProvider provideIgnoreData
     */
    public function testIgnore(array $input, int $expectedId): void
    {
        $stub = IgnoreSilentStub::from($input);
        $this->assertEquals('abc', $stub->uuid);
        $this->assertEquals($expectedId, $stub->id);
    }

    public function provideIgnoreData(): iterable
    {
        yield [['uuid' => 'xyz', 'id' => 42], 42];
        yield [['id' => 23], 23];
    }
}
