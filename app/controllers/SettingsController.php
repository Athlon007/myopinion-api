<?php

namespace Controllers;

use Services\SettingsService;
use Exception;

class SettingsController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new SettingsService();
    }

    public function getAll()
    {
        $token = $this->checkForJwt();
        if (!$token) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        // only admin can see all settings
        if (!$this->checkIfTokenHolderIsAdmin($token)) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        try {
            $settings = $this->service->getSettings();
            $this->respond($settings);
        } catch (Exception $e) {
            $this->respondWithError(500, "Unable to get settings.");
        }
    }

    public function update()
    {
        $token = $this->checkForJwt();
        if (!$token) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        // only admin can update settings
        if (!$this->checkIfTokenHolderIsAdmin($token)) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        $inputSettings = $this->createObjectFromPostedJson("Settings", true);

        try {
            $settings = $this->service->update($inputSettings);
            $this->respond($settings);
        } catch (Exception $e) {
            $this->respondWithError(500, "Unable to update settings.");
        }
    }

    public function forceNextTopic()
    {
        $token = $this->checkForJwt();
        if (!$token) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        // only admin can force next topic
        if (!$this->checkIfTokenHolderIsAdmin($token)) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        try {
            $settings = $this->service->forceNextTopic();
            $this->respond($settings);
        } catch (Exception $e) {
            $this->respondWithError(500, "Unable to force next topic.");
        }
    }
}
