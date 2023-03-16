<?php

namespace Services;

use Models\Topic;
use Repositories\TopicRepository;
use Models\Exceptions\IllegalOperationException;
use Services\SettingsService;

class TopicService
{
    private TopicRepository $repo;

    public function __construct()
    {
        $this->repo = new TopicRepository();
    }

    public function getAll()
    {
        return $this->repo->getAll();
    }

    public function getNthTopic($n): ?Topic
    {
        return $this->repo->getNthTopic($n);
    }

    public function isAnyTopicPresent(): bool
    {
        return $this->repo->getCount() > 0;
    }

    public function editTopicTitle(int $id, string $title): void
    {
        require_once("SettingsService.php");
        $settingService = new SettingsService();
        if ($settingService->getSettings()->getSelectedTopic()->getId() == $id) {
            throw new IllegalOperationException("Unable to edit currently active topic.");
        }

        $id = htmlspecialchars($id);
        $title = htmlspecialchars($title);
        $this->repo->update($id, $title);
    }

    public function addTopic(Topic $topic): ?Topic
    {
        $title = htmlspecialchars($topic->getName());

        if (strlen($title) == 0) {
            throw new IllegalOperationException("Cannot add empty topics.");
        }

        $id = $this->repo->insert($title);

        if ($id == null) {
            return null;
        }

        return $this->getTopicById($id);
    }

    public function update(Topic $topic): Topic
    {
        $id = htmlspecialchars($topic->getId());
        $title = htmlspecialchars($topic->getName());

        if (strlen($title) == 0) {
            throw new IllegalOperationException("Cannot add empty topics.");
        }

        $this->repo->update($id, $title);

        return $this->getTopicById($id);
    }

    public function getTopicById(int $id): Topic
    {
        $id = htmlspecialchars($id);
        if ($this->repo->getCountById($id) == 0) {
            require_once("../models/Exceptions/IllegalOperationException.php");
            throw new IllegalOperationException("Topic $id does not exist.");
        }
        return $this->repo->getById($id);
    }

    public function deleteById(int $id): void
    {
        $settingsService = new SettingsService();
        if ($settingsService->getSettings()->getSelectedTopic()->getId() == $id) {
            require_once("../models/Exceptions/IllegalOperationException.php");
            throw new IllegalOperationException("Unable to delete currently active topic.");
        }
        $id = htmlspecialchars($id);
        $this->repo->delete($id);
    }

    public function getTopicCount()
    {
        return $this->repo->getCount();
    }

    public function isTopicWithIdPresent(int $id): bool
    {
        $id = htmlspecialchars($id);
        return $this->repo->getCountById($id) > 0;
    }
}
