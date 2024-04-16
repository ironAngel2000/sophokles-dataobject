<?php
declare(strict_types=1);

use Sophokles\Dataobject\AbstractDataObject;

class BasicModel extends AbstractDataObject
{
    private string $myName = '';
    private int $myAge = 0;
    private float $myMoneyInPocket = 0.0;
    private bool $happy = false;
    private array $myFriends = [];

    public function getMyName(): string
    {
        return $this->myName;
    }

    public function setMyName(string $myName): void
    {
        $this->myName = $myName;
    }

    public function getMyAge(): int
    {
        return $this->myAge;
    }

    public function setMyAge(int $myAge): void
    {
        $this->myAge = $myAge;
    }

    public function getMyMoneyInPocket(): float
    {
        return $this->myMoneyInPocket;
    }

    public function setMyMoneyInPocket(float $myMoneyInPocket): void
    {
        $this->myMoneyInPocket = $myMoneyInPocket;
    }

    public function isHappy(): bool
    {
        return $this->happy;
    }

    public function setHappy(bool $happy): void
    {
        $this->happy = $happy;
    }

    public function getMyFriends(): array
    {
        return $this->myFriends;
    }

    public function setMyFriends(array $friends): void
    {
        $this->myFriends = $friends;
    }

    public function toArray(): array
    {
        return [
            'myName' => $this->getMyName(),
            'myAge' => $this->getMyAge(),
            'myMoneyInPocket' =>  $this->getMyMoneyInPocket(),
            'happy' => $this->isHappy(),
            'myFriends' => $this->getMyFriends(),
        ];
    }
}