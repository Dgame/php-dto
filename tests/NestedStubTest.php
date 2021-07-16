<?php

declare(strict_types=1);

namespace Dgame\DataTransferObject\Tests;

use Dgame\DataTransferObject\Tests\Stubs\NestedStub;
use PHPUnit\Framework\TestCase;

final class NestedStubTest extends TestCase
{
    public function testNestedObjects(): void
    {
        $input = ['limit' => ['offset' => 23, 'size' => 42], 'match' => ['query' => 'ab|c']];
        $stub = NestedStub::from($input);
        $this->assertInstanceOf(NestedStub::class, $stub);
        $this->assertEquals(23, $stub->limit->getFrom());
        $this->assertEquals(42, $stub->limit->getSize());
        $this->assertEquals('ab|c', $stub->query->getQuery());
    }
}
