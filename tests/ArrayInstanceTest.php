<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\ArrayInstanceStub;
use Dgame\DataTransferObject\Tests\Stubs\LimitStub;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Throwable;

final class ArrayInstanceTest extends TestCase
{
    /**
     * @param array       $input
     * @param string|null $message
     *
     * @throws ReflectionException
     * @throws Throwable
     * @dataProvider provideArrayData
     */
    public function testArrayInstance(array $input, ?string $message = null): void
    {
        if ($message !== null) {
            $this->expectException(InvalidArgumentException::class);
            $this->expectExceptionMessage($message);
        }

        $stub = ArrayInstanceStub::from($input);
        $this->assertContainsOnlyInstancesOf(LimitStub::class, $stub->limits);
    }

    public function provideArrayData(): iterable
    {
        yield [['limits' => [new LimitStub(from: 42, size: 23)]]];
        yield [['limits' => [new LimitStub(from: 1, size: 2), new LimitStub(from: 3, size: 4)]]];
        yield [['limits' => []]];
        yield [['limits' => [null]], 'NULL must be an object-instance of ' . LimitStub::class];
    }
}
