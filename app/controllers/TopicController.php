<?php

namespace Controllers;

use Services\TopicService;

class TopicController extends Controller
{
    private TopicService $service;

    public function __construct()
    {
        $this->service = new TopicService();
    }

    public function getAll()
    {
        $topics = $this->service->getAll();
        $this->respond($topics);
    }

    public function getTopic()
    {
        $id = basename($_SERVER['REQUEST_URI']);
        $topic = $this->service->getTopicById($id);

        $this->respond($topic);
    }

    public function insertTopic()
    {
        $token = $this->checkForJwt();
        if (!$token) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        if (!$this->checkIfTokenHolderIsAdmin($token)) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        $inputTopic = $this->createObjectFromPostedJson("Topic");

        $topic = $this->service->addTopic($inputTopic);

        if ($topic == null) {
            $this->respondWithError(500, "Unable to add topic.");
            return;
        }

        $this->respond($topic);
    }

    public function update()
    {
        $token = $this->checkForJwt();
        if (!$token) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        if (!$this->checkIfTokenHolderIsAdmin($token)) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }
    }
}
