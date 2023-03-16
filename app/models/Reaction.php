<?php

namespace Models;

use JsonSerializable;
use Models\ReactionEntity;
use Models\Opinion;

class Reaction implements JsonSerializable
{
    private int $id;
    private ReactionEntity $reactionEntity;
    private Opinion $opinion;
    private int $count;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): void
    {
        $this->id = $value;
    }

    public function getReactionEntity(): ReactionEntity
    {
        return $this->reactionEntity;
    }

    public function setReactionEntity(ReactionEntity $value): void
    {
        $this->reactionEntity = $value;
    }

    public function getOpinion(): Opinion
    {
        return $this->opinion;
    }

    public function setOpinion(Opinion $value): void
    {
        $this->opinion = $value;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $value): void
    {
        $this->count = $value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "id" => $this->getId(),
            "reaction_entity" => $this->getReactionEntity(),
            "count" => $this->getCount()
        ];
    }
}
