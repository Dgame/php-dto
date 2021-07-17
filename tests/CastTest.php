<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\CastStub;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Throwable;

final class CastTest extends TestCase
{
    /**
     * @param array $input
     * @param int   $expectedId
     *
     * @throws ReflectionException
     * @throws Throwable
     *
     * @dataProvider provideCastData
     */
    public function testCast(array $input, int $expectedId): void
    {
        $stub = CastStub::from($input);
        $this->assertEquals($expectedId, $stub->id);
    }

    public function provideCastData(): iterable
    {
        yield [['id' => 42], 42];
        yield [['id' => true], 1];
        yield [['id' => false], 0];
        yield [['id' => '43'], 43];
        yield [['id' => '43a'], 43];
        yield [['id' => '  43a'], 43];
        yield [['id' => '-43a'], -43];
        yield [['id' => 3.14], 3];
    }
}
