<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\NameAliasMixStub;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Throwable;

final class NameAliasMixTest extends TestCase
{
    /**
     * @param array $input
     * @param int   $expectedId
     *
     * @throws ReflectionException
     * @throws Throwable
     * @dataProvider provideNameAliasMixedData
     */
    public function testNameAliasMix(array $input, int $expectedId, int $expectedUuid): void
    {
        if (array_key_exists('id', $input)) {
            $this->expectException(InvalidArgumentException::class);
            $this->expectExceptionMessage('Expected one of "a, z"');
        }

        $stub = NameAliasMixStub::from($input);
        $this->assertEquals($expectedId, $stub->id);
        $this->assertEquals($expectedUuid, $stub->uuid);
    }

    public function provideNameAliasMixedData(): iterable
    {
        yield [['a' => 42, 'x' => 1], 42, 1];
        yield [['z' => 23, 'y' => 2], 23, 2];
        yield [['a' => 1337, 'uuid' => 3], 1337, 3];
        yield [['z' => 1337, 'uuid' => 4], 1337, 4];
        yield [['id' => 1337, 'uuid' => 42], 1337, 42];
    }
}
