<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\ValidationStub;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ValidationTest extends TestCase
{
    /**
     * @param array $input
     * @param int   $expectedAge
     *
     * @dataProvider provideValidAge
     */
    public function testValidAge(array $input, int $expectedAge): void
    {
        $vs = ValidationStub::from($input);
        $this->assertEquals($expectedAge, $vs->getAge());
    }

    /**
     * @param array  $input
     * @param string $message
     *
     * @dataProvider provideInvalidAge
     */
    public function testInvalidAge(array $input, string $message): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);
        ValidationStub::from($input);
    }

    public function provideValidAge(): iterable
    {
        yield [['age' => 18], 18];
        yield [['age' => 25], 25];
        yield [['age' => 42], 42];
        yield [['age' => 99], 99];
        yield [['age' => 125], 125];
    }

    public function provideInvalidAge(): iterable
    {
        yield [['age' => 'abc'], '\'abc\' must be a numeric value'];
        yield [['age' => 0], '0 must be >= 18'];
        yield [['age' => 16], '16 must be >= 18'];
        yield [['age' => 126], '126 must be <= 125'];
        yield [['age' => 255], '255 must be <= 125'];
    }
}
