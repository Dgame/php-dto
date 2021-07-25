<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\OptionalStub;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Throwable;

final class OptionalTest extends TestCase
{
    /**
     * @param array        $input
     * @param OptionalStub $expected
     *
     * @throws ReflectionException
     * @throws Throwable
     *
     * @dataProvider provideOptionalData
     */
    public function testOptional(array $input, OptionalStub $expected): void
    {
        $stub = OptionalStub::from($input);
        $this->assertEquals($expected, $stub);
    }

    public function provideOptionalData(): iterable
    {
        yield [[], new OptionalStub(id: 0, answer: 42, question: null, message: 'foobar', age: 18)];
        yield [['age' => 21], new OptionalStub(id: 0, answer: 42, question: null, message: 'foobar', age: 21)];
        yield [['id' => 23], new OptionalStub(id: 23, answer: 42, question: null, message: 'foobar', age: 18)];
        yield [['answer' => 23], new OptionalStub(id: 0, answer: 23, question: null, message: 'foobar', age: 18)];
        yield [['id' => 1337, 'answer' => 23], new OptionalStub(id: 1337, answer: 23, question: null, message: 'foobar', age: 18)];
        yield [['id' => 1337, 'answer' => 23, 'message' => 'quatz'], new OptionalStub(id: 1337, answer: 23, question: null, message: 'quatz', age: 18)];
        yield [['id' => 1337, 'answer' => 23, 'question' => 'quatz'], new OptionalStub(id: 1337, answer: 23, question: 'quatz', message: 'foobar', age: 18)];
    }
}
