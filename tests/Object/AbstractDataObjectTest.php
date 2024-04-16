<?php
declare(strict_types=1);

namespace Sophokles\Dataobject\Tests\Object;

use Sophokles\Dataobject\AbstractDataObject;
use PHPUnit\Framework\TestCase;

class AbstractDataObjectTest extends TestCase
{
    public function testImportStrings(): void
    {
        $stringModel = $this->getStringModel();

        $stringModel->init(['value' => "expected String", 'nullValue' => null]);
        $this->assertSame('expected String', $stringModel->getValue());
        $this->assertSame(null, $stringModel->getNullValue());

        $stringModel->init(['value' => 0, 'nullValue' => 'someValue']);
        $this->assertSame('0', $stringModel->getValue());
        $this->assertSame('someValue', $stringModel->getNullValue());

        $stringModel->init(['value' => true]);
        $this->assertSame('1', $stringModel->getValue());
    }

    public function testImportInteger(): void
    {
        $intModel = $this->getIntegerModel();

        $intModel->init(['value' => 1234, 'nullValue' => null]);
        $this->assertSame(1234, $intModel->getValue());
        $this->assertSame(null, $intModel->getNullValue());

        $intModel->init(['value' => '87654', 'nullValue' => 12356]);
        $this->assertSame(87654, $intModel->getValue());
        $this->assertSame(12356, $intModel->getNullValue());

        $intModel->init(['value' => 34.78]);
        $this->assertSame(34, $intModel->getValue());

        $intModel->init(['value' => 0.0]);
        $this->assertSame(0, $intModel->getValue());

        $intModel->init(['value' => '0.0']);
        $this->assertSame(0, $intModel->getValue());

        $intModel->init(['value' => '0']);
        $this->assertSame(0, $intModel->getValue());

        $intModel->init(['value' => '-02587']);
        $this->assertSame(-2587, $intModel->getValue());
    }

    public function testImportFloat(): void
    {
        $floatModel = $this->getFloatModel();

        $floatModel->init(['value' => 123.5678, 'nullValue' => null]);
        $this->assertSame(123.5678, $floatModel->getValue());
        $this->assertSame(null, $floatModel->getNullValue());

        $floatModel->init(['value' => 5678, 'nullValue' => 123.678]);
        $this->assertSame(5678.0, $floatModel->getValue());
        $this->assertSame(123.678, $floatModel->getNullValue());

        $floatModel->init(['value' => '-0100.456']);
        $this->assertSame(-100.456, $floatModel->getValue());

        $floatModel->init(['value' => '']);
        $this->assertSame(0.0, $floatModel->getValue());

        $floatModel->init(['value' => false]);
        $this->assertSame(0.0, $floatModel->getValue());

        $floatModel->init(['value' => true]);
        $this->assertSame(1.0, $floatModel->getValue());

        $floatModel->init(['value' => 0]);
        $this->assertSame(0.0, $floatModel->getValue());
    }

    public function testImportBoolean(): void
    {
        $boolModel = $this->getBooleanModel();

        $boolModel->init(['value' => true]);
        $this->assertTrue($boolModel->isValue());

        $boolModel->init(['value' => '1']);
        $this->assertTrue($boolModel->isValue());

        $boolModel->init(['value' => 'true']);
        $this->assertTrue($boolModel->isValue());

        $boolModel->init(['value' => 1.1]);
        $this->assertTrue($boolModel->isValue());

        $boolModel->init(['value' => false]);
        $this->assertFalse($boolModel->isValue());

        $boolModel->init(['value' => 'foobar']);
        $this->assertFalse($boolModel->isValue());

        $boolModel->init(['value' => 0.0]);
        $this->assertFalse($boolModel->isValue());

        $boolModel->init(['value' => 2.0]);
        $this->assertFalse($boolModel->isValue());

        $boolModel->init(['value' => 0]);
        $this->assertFalse($boolModel->isValue());
    }

    public function testImportSimpleArray(): void
    {
        $arrayModel = $this->getArrayModel();

        $testArray = ['a','b','c'];
        $testArrayNull = null;
        $arrayModel->init(['value' => $testArray, 'nullValue' => $testArrayNull]);
        $this->assertSame($testArray,  $arrayModel->getValue());
        $this->assertSame($testArrayNull, $arrayModel->getNullValue());

        $testArray = ['a',1, false];
        $testArrayNull = ['a','b','c'];
        $arrayModel->init(['value' => $testArray, 'nullValue' => $testArrayNull]);
        $this->assertSame($testArray,  $arrayModel->getValue());
        $this->assertSame($testArrayNull, $arrayModel->getNullValue());

        $arrayModel->init(['value' => []]);
        $this->assertSame([],  $arrayModel->getValue());

        $arrayModel->init(['value' => 'foobar']);
        $this->assertSame([],  $arrayModel->getValue());

        $arrayModel->init(['value' => '["a", "b", "c"]']);
        $this->assertSame([],  $arrayModel->getValue());

        $arrayModel->init(['value' => true]);
        $this->assertSame([],  $arrayModel->getValue());
    }


    private function getStringModel(): AbstractDataObject
    {
        return new Class() extends AbstractDataObject
        {
            private string $value = '';
            private ?string $nullValue = null;

            public function getValue(): string
            {
                return $this->value;
            }

            public function setValue(string $value): void
            {
                $this->value = $value;
            }

            public function getNullValue(): ?string
            {
                return $this->nullValue;
            }
            public function setNullValue(?string $nullValue): void
            {
                $this->nullValue = $nullValue;
            }

            public function toArray(): array
            {
                return [
                    'value' => $this->getValue(),
                    'nullValue' => $this->getNullValue(),
                ];
            }
        };
    }

    private function getIntegerModel(): AbstractDataObject
    {
        return new Class() extends AbstractDataObject
        {
            private int $value = 0;
            private ?int $nullValue = null;

            public function getValue(): int
            {
                return $this->value;
            }

            public function setValue(int $value): void
            {
                $this->value = $value;
            }

            public function getNullValue(): ?int
            {
                return $this->nullValue;
            }
            public function setNullValue(?int $nullValue): void
            {
                $this->nullValue = $nullValue;
            }
            public function toArray(): array
            {
                return [
                    'value' => $this->getValue(),
                    'nullValue' => $this->getNullValue(),
                ];
            }
        };
    }

    private function getFloatModel(): AbstractDataObject
    {
        return new Class() extends AbstractDataObject
        {
            private float $value = 0.0;
            private ?float $nullValue = null;


            public function getValue(): float
            {
                return $this->value;
            }

            public function setValue(float $value): void
            {
                $this->value = $value;
            }

            public function getNullValue(): ?float
            {
                return $this->nullValue;
            }
            public function setNullValue(?float $nullValue): void
            {
                $this->nullValue = $nullValue;
            }

            public function toArray(): array
            {
                return [
                    'value' => $this->getValue(),
                    'nullValue' => $this->getNullValue(),
                ];
            }
        };
    }

    private function getBooleanModel(): AbstractDataObject
    {
        return new Class() extends AbstractDataObject
        {
            private bool $value = false;

            public function isValue(): bool
            {
                return $this->value;
            }

            public function setValue(bool $value): void
            {
                $this->value = $value;
            }

            public function toArray(): array
            {
                return [
                    'value' => $this->isValue(),
                ];
            }
        };
    }

    private function getArrayModel(): AbstractDataObject
    {
        return new Class() extends AbstractDataObject
        {
            private array $value = [];
            private ?array $nullValue = null;

            public function getValue(): array
            {
                return $this->value;
            }

            public function setValue(array $value): void
            {
                $this->value = $value;
            }

            public function getNullValue(): ?array
            {
                return $this->nullValue;
            }
            public function setNullValue(?array $nullValue): void
            {
                $this->nullValue = $nullValue;
            }

            public function toArray(): array
            {
                return [
                    'value' => $this->getValue(),
                    'nullValue' => $this->getNullValue(),
                ];
            }
        };
    }
}