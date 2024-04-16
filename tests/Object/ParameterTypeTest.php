<?php
declare(strict_types=1);

namespace Sophokles\Dataobject\Tests\Object;

use Sophokles\Dataobject\ParameterType;
use PHPUnit\Framework\TestCase;

class ParameterTypeTest extends TestCase
{
    public function testValidNotNullable(): void
    {
        $parameterType = ParameterType::create('string');
        $this->assertEquals('string', $parameterType->type);
        $this->assertFalse($parameterType->isNullable);
        $this->assertEquals('string', $parameterType);
    }

    public function testValidNullable(): void
    {
        $parameterType = ParameterType::create('string', true);
        $this->assertEquals('string', $parameterType->type);
        $this->assertTrue($parameterType->isNullable);
        $this->assertEquals('string', $parameterType);
    }

    public function testInvalid(): void
    {
        $this->expectException(\Exception::class);
        $parameterType = ParameterType::create('');
    }
}