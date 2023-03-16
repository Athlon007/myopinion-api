<?php

namespace Controllers;

use Exception;
use Models\Topic;
use Services\OpinionService;
use Services\SettingsService;

class OpinionController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new OpinionService();
    }

    public function getAllForTopic()
    {
        // id of topic is second last part of the url
        $id = basename(dirname($_SERVER['REQUEST_URI']));

        $topic = new Topic();
        $topic->setId($id);

        $offset = -1;
        if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
            $offset = $_GET["offset"];
        }

        $limit = -1;
        if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $limit = $_GET["limit"];
        }

        $opinions = null;
        if (isset($_GET["sort"]) && $_GET["sort"] == "new") {
            $opinions = $this->service->getOpinionsForTopicByNew($topic, $offset, $limit);
        } else {
            $opinions = $this->service->getOpinionsForTopicByPopular($topic, $offset, $limit);
        }

        $this->respond($opinions);
    }

    public function getTodayOpinions()
    {
        $settingsService = new SettingsService();
        $settings = $settingsService->getSettings();

        $topic = $settings->getSelectedTopic();

        $offset = -1;
        if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
            $offset = $_GET["offset"];
        }

        $limit = -1;
        if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $limit = $_GET["limit"];
        }

        $opinions = null;
        if (isset($_GET["sort"]) && $_GET["sort"] == "new") {
            $opinions = $this->service->getOpinionsForTopicByNew($topic, $offset, $limit);
        } else {
            $opinions = $this->service->getOpinionsForTopicByPopular($topic, $offset, $limit);
        }

        $this->respond($opinions);
    }

    public function get()
    {
        $id = basename($_SERVER['REQUEST_URI']);
        $opinion = $this->service->getOpinionById($id);

        $this->respond($opinion);
    }

    public function insert()
    {
        $inputOpinion = $this->createObjectFromPostedJson("Opinion");

        $settingsService = new SettingsService();
        $settings = $settingsService->getSettings();
        $selectedTopic = $settings->getSelectedTopic();

        try {
            $opinion = $this->service->insertOpinion($inputOpinion, $selectedTopic);

            if ($opinion == null) {
                $this->respondWithError(500, "Unable to add opinion.");
                return;
            }

            $this->respond($opinion);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function update()
    {
        $token = $this->checkForJwt();
        if (!$token) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        if (!$this->checkIfTokenHolderIsAdmin($token) && !$this->checkIfTokenHolderIsModerator($token)) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        $inputOpinion = $this->createObjectFromPostedJson("Opinion", true);

        try {
            $opinion = $this->service->update($inputOpinion);

            if ($opinion == null) {
                $this->respondWithError(500, "Unable to update opinion.");
                return;
            }

            $this->respond($opinion);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function delete()
    {
        $token = $this->checkForJwt();
        if (!$token) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        if (!$this->checkIfTokenHolderIsAdmin($token) && !$this->checkIfTokenHolderIsModerator($token)) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        $id = basename($_SERVER['REQUEST_URI']);

        try {
            $this->service->deleteById($id);
            $this->respond(["message" => "Opinion deleted."]);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }
}
