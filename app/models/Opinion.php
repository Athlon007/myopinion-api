<?php

namespace Models;

use JsonSerializable;

class Opinion implements JsonSerializable
{
    private int $id;
    private string $title;
    private string $content;
    private array $reactions;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): void
    {
        $this->id = $value;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $value): void
    {
        $this->title = $value;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $value): void
    {
        $this->content = $value;
    }

    public function getReactions(): array
    {
        return $this->reactions;
    }

    public function setReactions(array $value): void
    {
        $this->reactions = $value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "content" => $this->content,
            "reactions" => $this->reactions
        ];
    }
}
