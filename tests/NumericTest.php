<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\NumericStub;
use Dgame\DataTransferObject\ValidationException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Throwable;

final class NumericTest extends TestCase
{
    /**
     * @param array       $input
     * @param NumericStub $expected
     *
     * @throws ReflectionException
     * @throws Throwable
     *
     * @dataProvider provideNumeric
     */
    public function testNumeric(array $input, NumericStub $expected): void
    {
        $stub = NumericStub::from($input);
        $this->assertEquals($expected, $stub);
    }

    /**
     * @param array $input
     *
     * @throws ReflectionException
     * @throws Throwable
     *
     * @dataProvider provideNonNumeric
     */
    public function testNonNumeric(array $input): void
    {
        $this->expectException(ValidationException::class);
        NumericStub::from($input);
    }

    public function provideNumeric(): iterable
    {
        yield [['int' => '123'], (static function (): NumericStub {
            $stub      = new NumericStub();
            $stub->int = 123;

            return $stub;
        })()];

        yield [['int' => ' -4'], (static function (): NumericStub {
            $stub      = new NumericStub();
            $stub->int = -4;

            return $stub;
        })()];

        yield [['int' => true], (static function (): NumericStub {
            $stub      = new NumericStub();
            $stub->int = 1;

            return $stub;
        })()];

        yield [['float' => '42'], (static function (): NumericStub {
            $stub        = new NumericStub();
            $stub->float = 42.0;

            return $stub;
        })()];

        yield [['float' => '4.2'], (static function (): NumericStub {
            $stub        = new NumericStub();
            $stub->float = 4.2;

            return $stub;
        })()];

        yield [['float' => (string) M_PI], (static function (): NumericStub {
            $stub        = new NumericStub();
            $stub->float = M_PI;

            return $stub;
        })()];
    }

    public function provideNonNumeric(): iterable
    {
        yield [['int' => 'a123'], (static function (): NumericStub {
            $stub      = new NumericStub();
            $stub->int = 123;

            return $stub;
        })()];

        yield [['int' => '123a'], (static function (): NumericStub {
            $stub      = new NumericStub();
            $stub->int = 123;

            return $stub;
        })()];

        yield [['int' => ' '], (static function (): NumericStub {
            $stub      = new NumericStub();
            $stub->int = 0;

            return $stub;
        })()];

        yield [['int' => ' - 4'], (static function (): NumericStub {
            $stub      = new NumericStub();
            $stub->int = -4;

            return $stub;
        })()];

        yield [['int' => '4 2'], (static function (): NumericStub {
            $stub      = new NumericStub();
            $stub->int = 42;

            return $stub;
        })()];

        yield [['int' => M_PI], (static function (): NumericStub {
            $stub      = new NumericStub();
            $stub->int = 3;

            return $stub;
        })()];
    }
}
