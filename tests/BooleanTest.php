<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\BooleanStub;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Throwable;

final class BooleanTest extends TestCase
{
    /**
     * @param array       $input
     * @param BooleanStub $expceted
     *
     * @throws ReflectionException
     * @throws Throwable
     *
     * @dataProvider provideBooleans
     */
    public function testBoolean(array $input, BooleanStub $expceted): void
    {
        $stub = BooleanStub::from($input);
        $this->assertEquals($expceted, $stub);
    }

    public function provideBooleans(): iterable
    {
        yield [['yes' => 'yes'], (static function(): BooleanStub {
            $stub = new BooleanStub();
            $stub->yes = true;

            return $stub;
        })()];

        yield [['no' => 'no'], (static function(): BooleanStub {
            $stub = new BooleanStub();
            $stub->no = false;

            return $stub;
        })()];

        yield [['on' => 'on'], (static function(): BooleanStub {
            $stub = new BooleanStub();
            $stub->on = true;

            return $stub;
        })()];

        yield [['off' => 'off'], (static function(): BooleanStub {
            $stub = new BooleanStub();
            $stub->off = false;

            return $stub;
        })()];

        yield [['one' => 1], (static function(): BooleanStub {
            $stub = new BooleanStub();
            $stub->one = true;

            return $stub;
        })()];

        yield [['zero' => 0], (static function(): BooleanStub {
            $stub = new BooleanStub();
            $stub->zero = false;

            return $stub;
        })()];

        yield [['yes' => true], (static function(): BooleanStub {
            $stub = new BooleanStub();
            $stub->yes = true;

            return $stub;
        })()];

        yield [['zero' => false], (static function(): BooleanStub {
            $stub = new BooleanStub();
            $stub->zero = false;

            return $stub;
        })()];

        yield [['yes' => 'true'], (static function(): BooleanStub {
            $stub = new BooleanStub();
            $stub->yes = true;

            return $stub;
        })()];

        yield [['zero' => 'false'], (static function(): BooleanStub {
            $stub = new BooleanStub();
            $stub->zero = false;

            return $stub;
        })()];

        yield [['one' => '1'], (static function(): BooleanStub {
            $stub = new BooleanStub();
            $stub->one = true;

            return $stub;
        })()];

        yield [['zero' => '0'], (static function(): BooleanStub {
            $stub = new BooleanStub();
            $stub->zero = false;

            return $stub;
        })()];
    }
}
