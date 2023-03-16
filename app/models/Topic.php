<?php

namespace Models;

use JsonSerializable;

class Topic implements JsonSerializable
{
    private int $id;
    private string $name;
    private array $opinions;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId($value): void
    {
        $this->id = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($value): void
    {
        $this->name = $value;
    }

    public function getOpinions(): array
    {
        return $this->opinions;
    }

    public function setOpinions($value): void
    {
        $this->opinions = $value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "opinions" => $this->getOpinions()
        ];
    }
}
