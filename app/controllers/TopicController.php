<?php

namespace Controllers;

use Exception;
use Services\TopicService;
use Models\Exceptions\IllegalOperationException;
use Services\SettingsService;

class TopicController extends Controller
{
    private TopicService $service;

    public function __construct()
    {
        $this->service = new TopicService();
    }

    public function getAll()
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

        $topics = $this->service->getAll();
        $this->respond($topics);
    }

    public function getTopic()
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

        $id = basename($_SERVER['REQUEST_URI']);

        try {
            $topic = $this->service->getTopicById($id);
            $this->respond($topic);
        } catch (Exception $e) {
            $this->respondWithError(500, "Unable to get topic.");
        }
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

        $inputTopic = $this->createObjectFromPostedJson("Topic", true);

        try {
            $topic = $this->service->update($inputTopic);
            $this->respond($topic);
        } catch (IllegalOperationException $e) {
            $this->respondWithError(400, $e->getMessage());
            return;
        }
    }

    public function delete()
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

        $id = basename($_SERVER['REQUEST_URI']);

        try {
            $this->service->deleteById($id);
            $this->respond(["message" => "Topic deleted successfully."]);
        } catch (IllegalOperationException $e) {
            $this->respondWithError(400, $e->getMessage());
            return;
        }
    }

    public function getTodayTopic()
    {
        $settingsService = new SettingsService();
        $settings = $settingsService->getSettings();
        $this->respond($settings->getSelectedTopic());
    }
}
