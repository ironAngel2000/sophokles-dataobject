<?php
declare(strict_types=1);

namespace Sophokles\Dataobject;

class ParameterType
{
    private function __construct(public string $type, public bool $isNullable = false)
    {
        $this->mustBeValid();
    }

    public static function create(string $name, bool $isNullable = false): self
    {
        return new self($name, $isNullable);
    }

    public function isEqual(self $parameterType): bool
    {
        return $this->type === $parameterType->type && $this->isNullable === $parameterType->isNullable;
    }

    public function isNotEqualTo(self $parameterType): bool
    {
        return !$this->isEqual($parameterType);
    }

    public function __toString(): string
    {
        return $this->type;
    }

    private function mustBeValid(): void
    {
        if (empty($this->type)) {
            throw new \Exception('Parameter is not valid');
        }
    }
}