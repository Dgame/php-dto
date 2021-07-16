<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\RequiredStub;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Throwable;

final class RequiredTest extends TestCase
{
    /**
     * @param array  $input
     * @param int    $expectedId
     * @param string $exception
     *
     * @throws ReflectionException
     * @throws Throwable
     * @dataProvider provideAbsentData
     */
    public function testAbsent(array $input, int $expectedId, string $exception = InvalidArgumentException::class): void
    {
        if (!array_key_exists('id', $input)) {
            $this->expectException($exception);
            $this->expectExceptionMessage('We need an "id" to identify the class');
        }

        $stub = RequiredStub::from($input);
        $this->assertEquals($expectedId, $stub->id);
    }

    public function provideAbsentData(): iterable
    {
        yield [['id' => 23], 23];
        yield [[], 23];
    }
}
