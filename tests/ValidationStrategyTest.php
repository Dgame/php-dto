<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\ValidationStrategyStub;
use Dgame\DataTransferObject\ValidationException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Throwable;

final class ValidationStrategyTest extends TestCase
{
    /**
     * @param array  $input
     * @param string $message
     *
     * @throws ReflectionException
     * @throws Throwable
     * @dataProvider provideValidationStrategyData
     */
    public function testValidationStrategy(array $input, string $message): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($message);

        ValidationStrategyStub::from($input);
    }

    public function provideValidationStrategyData(): iterable
    {
        yield [[], 'Expected a value for ValidationStrategyStub.name'];
        yield [['id' => -1], 'Expected a value for ValidationStrategyStub.name' . PHP_EOL . 'Value -1 of ValidationStrategyStub.id must be >= 0'];
        yield [['name' => 'FooBar', 'id' => -42], 'Value -42 of ValidationStrategyStub.id must be >= 0'];
        yield [['name' => 'a', 'id' => 42], 'Value \'a\' of ValidationStrategyStub.name must have at least a length of 3'];
        yield [['id' => 23], 'Expected a value for ValidationStrategyStub.name'];
    }
}
