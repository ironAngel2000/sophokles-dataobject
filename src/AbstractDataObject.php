<?php
declare(strict_types=1);

namespace Sophokles\Dataobject;

use ReflectionMethod;

abstract class AbstractDataObject
{
    abstract public function toArray(): array;

    public function __construct(array $data = [])
    {
        $this->init($data);
    }

    public function init(array $data): void
    {
        foreach ($data as $key => $value) {

            if (is_int($key) && is_array($value)) {
                $this->init($value);
                continue;
            }

            $this->fetchInitValue($key, $value);
        }
    }

    public function getSetter(string $field): string
    {
        $field = $this->keyToCamelCase($field);
        return 'set' . ucfirst($field);
    }

    public function getGetter(string $field): string
    {
        if (method_exists($this, $field)) {
            return $field;
        }

        $field = $this->keyToCamelCase($field);
        return 'get' . ucfirst($field);
    }

    public static function convertKey2CamelCase(string $key, bool $ucFirst = false): string
    {
        $arrSegments = explode('_', $key);
        $key = '';
        foreach ($arrSegments as $segment) {
            $key .= ucfirst($segment);
        }

        if ($ucFirst) {
            return ucfirst($key);
        }

        return lcfirst($key);
    }

    protected function fetchInitValue(string|int $key, mixed $value): void
    {
        $key = $this->normalizeKey($key);
        $setter = $this->getSetter($key);

        if (!method_exists($this, $setter)) {
            return;
        }

        $parmaType = $this->getArgumentType($setter);
        $value = $this->getValueFromEnum($value);

        switch ($parmaType->type) {
            case 'int':
                $this->setIntValue($setter, $value, $parmaType->isNullable);
                break;
            case 'float':
                $this->setFloatValue($setter, $value, $parmaType->isNullable);
                break;
            case 'bool':
                $this->setBooleanValue($setter, $value, $parmaType->isNullable);
                break;
            case 'array':
                $this->setArrayValue($setter, $value, $parmaType->isNullable);
                break;
            case 'string':
                //no break
            default:
                $this->setStringValue($setter, $value, $parmaType->isNullable);
                break;
        }
    }

    protected function normalizeKey(string $key): string
    {
        if (str_starts_with($key, '_')) {
            $key = substr($key, 1);
        }

        return str_replace('-', '_', $key);
    }

    protected function getValueFromEnum(mixed $value): mixed
    {
        if (is_object($value) && enum_exists($value::class)) {
            $value = $value->value;
        }

        return $value;
    }

    protected function keyToCamelCase(string $key)
    {
        return self::convertKey2CamelCase($key);
    }

    protected function getArgumentType(string $setter): ParameterType
    {
        $className = get_class($this);
        $reflection = new ReflectionMethod($className, $setter);
        $parameters = $reflection->getParameters();

        $setParameter = $parameters[0];

        $allowNull = false;
        try {
            $paramTypeHint = $setParameter->getType();
            $type = $paramTypeHint->getName();
            if ($paramTypeHint->allowsNull()) {
                $allowNull = true;
            }
        } catch (\Throwable $t) {
            $type = 'unknown';
        }

        return ParameterType::create($type, $allowNull);
    }

    protected function setArrayValue(string $setter, mixed $value, bool $nullAllow = false): void
    {
        if ($nullAllow && $value === null) {
            $this->$setter($value);
            return;
        }

        if (!is_array($value)) {
            $value = [];
        }
        $this->$setter($value);
    }

    protected function setStringValue(string $setter, mixed $value, bool $nullAllow = false): void
    {
        if ($nullAllow && $value === null) {
            $this->$setter($value);
            return;
        }

        $value = (string)$value;
        $this->$setter($value);
    }

    protected function setBooleanValue(string $setter, mixed $value): void
    {
        if (!is_bool($value)) {
            $value = (int)$value === 1 || strtolower((string)$value)==='true';
        }
        $this->$setter($value);
    }

    protected function setFloatValue(string $setter, mixed $value, bool $nullAllow = false): void
    {
        if ($nullAllow && $value === null) {
            $this->$setter($value);
            return;
        }
        $value = (float)$value;
        $this->$setter($value);
    }

    protected function setIntValue(string $setter, mixed $value, bool $nullAllow = false): void
    {
        if ($nullAllow && $value === null) {
            $this->$setter($value);
            return;
        }
        $value = (int)$value;
        $this->$setter($value);
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}