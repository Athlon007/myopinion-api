<?php

namespace Services;

use Models\Opinion;
use Models\Topic;
use Models\AccountType;
use Models\Account;
use Models\Exceptions\OpinionAlterException;
use Models\Exceptions\IllegalOperationException;

use Repositories\OpinionRepository;

class OpinionService
{
    private OpinionRepository $repo;
    private SettingsService $settingsService;

    public function __construct()
    {
        $this->repo = new OpinionRepository();
        $this->settingsService = new SettingsService();
    }

    public function getOpinionsForTopicByNew(Topic $topic, int $offset = -1, int $limit = -1)
    {
        if ($offset == -1) {
            $offset = $this->getPageOffset();
        }
        if ($limit == -1) {
            $limit = $this->getOpinionsLimit();
        }

        return $this->repo->getOpinionsForTopicByNew($topic, true, $offset, $limit);
    }

    public function getOpinionsForTopicByPopular(Topic $topic, int $offset = -1, int $limit = -1): array
    {
        if ($offset == -1) {
            $offset = $this->getPageOffset();
        }
        if ($limit == -1) {
            $limit = $this->getOpinionsLimit();
        }

        return $this->repo->getOpinionsForTopicByPopularity($topic, true, $offset, $limit);
    }

    public function insertOpinion(Opinion $opinion, Topic $topic): Opinion
    {
        $opinion->setTitle($opinion->getTitle());
        $opinion->setContent($opinion->getContent());

        $id = $this->repo->insertOpinion($topic->getId(), $opinion->getTitle(), $opinion->getContent());

        return $this->getOpinionById($id);
    }

    // Returns how many pages are there supposed to be for the specific topic.
    public function pagesForTopic(Topic $topic): int
    {
        $settings = $this->settingsService->getSettings();
        return ceil($this->repo->getOpinionsForTopicCount($topic) / $settings->getMaxReactionsPerPage());
    }

    public function getPageOffset()
    {
        $page = 1;
        if (isset($_GET) && isset($_GET["page"])) {
            $page = $_GET["page"];
        }

        $settings = $this->settingsService->getSettings();

        return ($page - 1) * $settings->getMaxReactionsPerPage();
    }

    public function getOpinionsLimit()
    {
        $settings = $this->settingsService->getSettings();
        return $settings->getMaxReactionsPerPage();
    }

    public function deleteById(int $id): void
    {
        $id = htmlspecialchars($id);
        $this->repo->deleteById($id);
    }

    public function update(Opinion $opinion): Opinion
    {
        $id = htmlspecialchars($opinion->getId());
        $title = htmlspecialchars($opinion->getTitle());
        $content = htmlspecialchars($opinion->getContent());

        if (strlen($title) == 0) {
            throw new OpinionAlterException("Title cannot be empty.");
        }
        if (strlen($content) == 0) {
            throw new OpinionAlterException("Content cannot be empty.");
        }

        $this->repo->update($id, $title, $content);

        return $this->getOpinionById($id);
    }

    public function getOpinionById(int $id): ?Opinion
    {
        $id = htmlspecialchars($id);
        return $this->repo->selectById($id);
    }
}
