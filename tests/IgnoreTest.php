<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\IgnoreWithExceptionStub;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Throwable;

final class IgnoreTest extends TestCase
{
    /**
     * @param array  $input
     * @param int    $expectedId
     * @param string $exception
     *
     * @throws ReflectionException
     * @throws Throwable
     * @dataProvider provideIgnoreData
     */
    public function testIgnore(array $input, int $expectedId, string $exception = InvalidArgumentException::class): void
    {
        if (array_key_exists('uuid', $input)) {
            $this->expectException($exception);
            $this->expectExceptionMessage('The uuid is not supposed to be set');
        }

        $stub = IgnoreWithExceptionStub::from($input);
        $this->assertEquals('abc', $stub->uuid);
        $this->assertEquals($expectedId, $stub->id);
    }

    public function provideIgnoreData(): iterable
    {
        yield [['uuid' => 'xyz', 'id' => 42], 42];
        yield [['id' => 23], 23];
    }
}
