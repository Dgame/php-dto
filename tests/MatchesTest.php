<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\MatchesStub;
use Dgame\DataTransferObject\ValidationException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Throwable;

final class MatchesTest extends TestCase
{
    /**
     * @param array       $input
     * @param MatchesStub $expected
     *
     * @throws ReflectionException
     * @throws Throwable
     *
     * @dataProvider provideMatches
     */
    public function testMatch(array $input, MatchesStub $expected): void
    {
        $stub = MatchesStub::from($input);
        $this->assertEquals($expected, $stub);
    }

    /**
     * @param array $input
     *
     * @throws ReflectionException
     * @throws Throwable
     *
     * @dataProvider provideNotMatches
     */
    public function testNotMatch(array $input): void
    {
        $this->expectException(ValidationException::class);
        MatchesStub::from($input);
    }

    public function provideMatches(): iterable
    {
        yield [['name' => 'Dagobert Duck'], (static function (): MatchesStub {
            $stub       = new MatchesStub();
            $stub->name = 'Dagobert Duck';

            return $stub;
        })()];

        yield [['name' => '  Daisy Duck  '], (static function (): MatchesStub {
            $stub       = new MatchesStub();
            $stub->name = 'Daisy Duck';

            return $stub;
        })()];

        yield [['name' => '  Foo'], (static function (): MatchesStub {
            $stub       = new MatchesStub();
            $stub->name = 'Foo';

            return $stub;
        })()];

        yield [['age' => 99], (static function (): MatchesStub {
            $stub      = new MatchesStub();
            $stub->age = 99;

            return $stub;
        })()];

        yield [['age' => 19], (static function (): MatchesStub {
            $stub      = new MatchesStub();
            $stub->age = 19;

            return $stub;
        })()];

        yield [['age' => 10], (static function (): MatchesStub {
            $stub      = new MatchesStub();
            $stub->age = 10;

            return $stub;
        })()];
    }

    public function provideNotMatches(): iterable
    {
        yield [['name' => 'A  '], (static function (): MatchesStub {
            $stub       = new MatchesStub();
            $stub->name = 'A';

            return $stub;
        })()];

        yield [['name' => '     '], (static function (): MatchesStub {
            $stub       = new MatchesStub();
            $stub->name = '';

            return $stub;
        })()];

        yield [['age' => 100], (static function (): MatchesStub {
            $stub      = new MatchesStub();
            $stub->age = 100;

            return $stub;
        })()];

        yield [['age' => 9], (static function (): MatchesStub {
            $stub      = new MatchesStub();
            $stub->age = 9;

            return $stub;
        })()];
    }
}
