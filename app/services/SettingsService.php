<?php

namespace Services;

use DateTime;
use Models\Settings;
use Repositories\SettingsRepository;
use Models\Exceptions\IllegalOperationException;

class SettingsService
{
    private SettingsRepository $repo;

    public function __construct()
    {
        $this->repo = new SettingsRepository();

        $topicService = new TopicService();

        if ($this->getSettings()->getSelectedTopic() == null) {
            $topicCount = $topicService->getTopicCount();
            if ($topicCount == 0) {
                return;
            }
            $this->repo->setSelectedNthTopic($topicCount - 1, date('y-m-d'));
        }

        if ($this->isTimeToChangeTopic()) {
            $this->changeTopicToNext();
        }
    }

    public function getSettings(): Settings
    {
        return $this->repo->getSettings();
    }

    public function isTimeToChangeTopic(): bool
    {
        $today = DateTime::createFromFormat('y-m-d', date('y-m-d'));
        $settings = $this->getSettings();
        $lastTimeUpdated = $settings->getDateLastTopicSelected();
        $result = $today > $lastTimeUpdated;
        return $result;
    }

    private function changeTopicToNext()
    {
        $settings = $this->getSettings();
        require_once("TopicService.php");
        $topicService = new TopicService();

        $nextID = $this->repo->getSelectedNthTopic() + 1;
        $topicsCount = $topicService->getTopicCount();
        if ($nextID >= $topicsCount) {
            // Do not overflow the selected opinion, instead go back to the beginning.
            $nextID = 0;
        }

        $this->repo->setSelectedNthTopic($nextID, date('y-m-d'));
    }

    /**
     * Forces the current topic to be abandoned and selects the next one from the list.
     */
    public function forceNextTopic(): void
    {
        $this->changeTopicToNext();
    }

    public function update(Settings $settings): Settings
    {
        $selectedNthTopic = htmlspecialchars($settings->getSelectedTopic()->getId());
        $dateLastTopicSelected = $settings->getDateLastTopicSelected()->format('y-m-d');

        $this->repo->update($selectedNthTopic, $dateLastTopicSelected);
        return $this->getSettings();
    }
}
