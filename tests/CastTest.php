<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\CastStub;
use Dgame\DataTransferObject\Tests\Stubs\StrictCastStub;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Throwable;

final class CastTest extends TestCase
{
    /**
     * @param array                        $input
     * @param CastStub|StrictCastStub|null $expected
     * @param Exception|null               $exception
     *
     * @throws ReflectionException
     * @throws Throwable
     * @dataProvider provideCastData
     */
    public function testCast(array $input, CastStub|StrictCastStub|null $expected, ?Exception $exception = null): void
    {
        if ($exception !== null) {
            $this->expectExceptionObject($exception);
        }

        $stub = $expected::from($input);
        $this->assertEquals($expected, $stub);
    }

    public function provideCastData(): iterable
    {
        yield [['id' => 42], new CastStub(id: 42)];
        yield [['id' => true], new CastStub(id: 1)];
        yield [['id' => false], new CastStub(id: 0)];
        yield [['id' => '43'], new CastStub(id: 43)];
        yield [['id' => '43a'], new CastStub(id: 43)];
        yield [['id' => '  43a'], new CastStub(id: 43)];
        yield [['id' => '-43a'], new CastStub(id: -43)];
        yield [['id' => 3.14], new CastStub(id: 3)];

        yield [['age' => 42], new CastStub(age: 42)];
        yield [['age' => true], new CastStub(age: 1)];
        yield [['age' => false], new CastStub(age: 0)];
        yield [['age' => '43'], new CastStub(age: 43)];
        yield [['age' => '43a'], new CastStub(age: 43)];
        yield [['age' => '  43a'], new CastStub(age: 43)];
        yield [['age' => '-43a'], new CastStub(age: -43)];
        yield [['age' => 3.14], new CastStub(age: 3)];
        yield [['age' => null], new CastStub(age: null)];

        yield [['uid' => 42], new CastStub(uid: 42)];
        yield [['uid' => true], new CastStub(uid: 1)];
        yield [['uid' => false], new CastStub(uid: 0)];
        yield [['uid' => '43'], new CastStub(uid: 43)];
        yield [['uid' => '43a'], new CastStub(uid: 43)];
        yield [['uid' => '  43a'], new CastStub(uid: 43)];
        yield [['uid' => '-43a'], new CastStub(uid: -43)];
        yield [['uid' => 3.14], new CastStub(uid: 3.14)];
        yield [['uid' => '3.14'], new CastStub(uid: 3)];
        yield [['uid' => null], new CastStub(uid: null)];

        yield [['age' => 42], new StrictCastStub(age: 42)];
        yield [['age' => true], new StrictCastStub(), new InvalidArgumentException("Only float is accepted, bool (true) given.")];
        yield [['age' => false], new StrictCastStub(), new InvalidArgumentException("Only float is accepted, bool (false) given.")];
        yield [['age' => '43'], new StrictCastStub(), new InvalidArgumentException("Only float is accepted, string ('43') given.")];
        yield [['age' => '43a'], new StrictCastStub(), new InvalidArgumentException("Only float is accepted, string ('43a') given.")];
        yield [['age' => '  43a'], new StrictCastStub(), new InvalidArgumentException("Only float is accepted, string ('  43a') given.")];
        yield [['age' => '-43a'], new StrictCastStub(), new InvalidArgumentException("Only float is accepted, string ('-43a') given.")];
        yield [['age' => 3.14], new StrictCastStub(age: 3)];
        yield [['age' => null], new StrictCastStub(age: null)];
    }
}
