<?php

namespace Models;

use JsonSerializable;

class AccountType implements JsonSerializable
{
    private int $id;
    private string $name;


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): void
    {
        $this->id = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $value): void
    {
        $this->name = $value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
        ];
    }
}
