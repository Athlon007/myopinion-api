<?php

namespace Services;

use Models\Opinion;
use Models\Topic;
use Models\Exceptions\OpinionAlterException;

use Repositories\OpinionRepository;

class OpinionService
{
    private OpinionRepository $repo;

    public function __construct()
    {
        $this->repo = new OpinionRepository();
    }

    public function getOpinionsForTopicByNew(Topic $topic, int $offset = -1, int $limit = -1)
    {
        return $this->repo->getOpinionsForTopicByNew($topic, true, $offset, $limit);
    }

    public function getOpinionsForTopicByPopular(Topic $topic, int $offset = -1, int $limit = -1): array
    {
        return $this->repo->getOpinionsForTopicByPopularity($topic, true, $offset, $limit);
    }

    public function insertOpinion(Opinion $opinion, Topic $topic): Opinion
    {
        $opinion->setTitle($opinion->getTitle());
        $opinion->setContent($opinion->getContent());

        $id = $this->repo->insertOpinion($topic->getId(), $opinion->getTitle(), $opinion->getContent());

        return $this->getOpinionById($id);
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
