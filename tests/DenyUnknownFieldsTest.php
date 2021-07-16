<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\DenyUnknownStub;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class DenyUnknownFieldsTest extends TestCase
{
    /**
     * @param array       $input
     * @param string|null $exception
     * @param string|null $message
     *
     * @dataProvider provideData
     */
    public function testDenyUnknown(array $input, ?string $exception, ?string $message): void
    {
        if ($exception === null && $message !== null) {
            $exception = InvalidArgumentException::class;
        }

        if ($exception !== null) {
            $this->expectException($exception);
        }

        if ($message !== null) {
            $this->expectExceptionMessage($message);
        }

        $stub = DenyUnknownStub::from($input);
        $this->assertInstanceOf(DenyUnknownStub::class, $stub);
    }

    public function provideData(): iterable
    {
        yield [['id' => 42], null, null];
        yield [['Id' => 42], null, 'The field "Id" is not expected'];
        yield [['a' => null, 'b' => null], null, 'The fields "a, b" are not expected'];
    }
}
