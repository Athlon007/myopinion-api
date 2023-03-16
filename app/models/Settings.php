<?php

namespace Models;

use DateTime;
use Models\Topic;

use JsonSerializable;

class Settings implements JsonSerializable
{
    private ?Topic $selectedTopic;
    private DateTime $dateLastTopicSelected;

    public function getSelectedTopic(): ?Topic
    {
        return $this->selectedTopic;
    }

    public function setSelectedTopic(Topic $value): void
    {
        $this->selectedTopic = $value;
    }

    public function getDateLastTopicSelected(): DateTime
    {
        return $this->dateLastTopicSelected;
    }

    public function setDateLastTopicSelected(DateTime $value): void
    {
        $this->dateLastTopicSelected = $value;
    }


    public function jsonSerialize(): mixed
    {
        return [
            "selected_topic" => $this->getSelectedTopic(),
            "date_last_topic_selected" => $this->getDateLastTopicSelected(),
        ];
    }
}
