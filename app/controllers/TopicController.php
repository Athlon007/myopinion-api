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

    function getTopic()
    {
        $id = basename($_SERVER['REQUEST_URI']);
        $topic = $this->service->getTopicById($id);

        $this->respond($topic);
    }

    function insertTopic()
    {
    }
}
