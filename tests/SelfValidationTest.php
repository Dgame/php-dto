<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\SelfValidationStub;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Throwable;

final class SelfValidationTest extends TestCase
{
    /**
     * @param array              $input
     * @param SelfValidationStub $expected
     *
     * @throws ReflectionException
     * @throws Throwable
     *
     * @dataProvider provideSelfValidationData
     */
    public function testSelfValidation(array $input, SelfValidationStub $expected): void
    {
        if ($expected->id <= 0) {
            $this->expectErrorMessage('assert($this->id > 0)');
        }

        $stub = SelfValidationStub::from($input);
        $this->assertEquals($expected, $stub);
    }

    public function provideSelfValidationData(): iterable
    {
        yield [['id' => 1], new SelfValidationStub(id: 1)];
        yield [['id' => 99], new SelfValidationStub(id: 99)];
        yield [['id' => 0], new SelfValidationStub(id: 0)];
        yield [['id' => -1], new SelfValidationStub(id: -1)];
    }
}
