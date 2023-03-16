<?php

namespace Models;

use DateTime;
use Models\Topic;

class Settings
{
    private ?Topic $selectedTopic;
    private DateTime $dateLastTopicSelected;
    private int $hideOptionsWithNReports;
    private int $maxReactionsPerPage;

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

    public function getHideOptionsWithNReports(): int
    {
        return $this->hideOptionsWithNReports;
    }

    public function setHideOptionsWithNReports(int $value): void
    {
        $this->hideOptionsWithNReports = $value;
    }

    public function getMaxReactionsPerPage(): int
    {
        return $this->maxReactionsPerPage;
    }

    public function setMaxReactionsPerPage(int $value): void
    {
        $this->maxReactionsPerPage = $value;
    }
}
