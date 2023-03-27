<?php

namespace Controllers;

use Exception;
use PDOException;
use Services\ReactionService;
use Services\OpinionService;
use Services\ReactionEntityService;

class ReactionController extends Controller
{
    private $service;
    private $entityService;

    public function __construct()
    {
        $this->service = new ReactionService();
        $this->entityService = new ReactionEntityService();
    }

    public function react()
    {
        // get opinion from basename
        $opinionID = basename($_SERVER['REQUEST_URI']);
        $opinionService = new OpinionService();

        try {
            $opinion = $opinionService->getOpinionById($opinionID);

            if ($opinion == null) {
                $this->respondWithError(404, "Opinion not found.");
                return;
            }

            $inputReaction = $this->createObjectFromPostedJson("ReactionEntity");

            $reaction = $this->service->addReaction($opinion, $inputReaction);
            $this->respond($reaction);
        } catch (PDOException $e) {
            $this->respondWithError(500, 'Only valid reactions are allowed."
                ." See /api/reactions for available reactions.');
        } catch (Exception $e) {
            $this->respondWithError(500, "Unable to insert reaction. " . $e->getMessage());
        }
    }

    public function getAvailableReactions()
    {
        try {
            $reactions = $this->entityService->getAll();
            $this->respond($reactions);
        } catch (Exception $e) {
            $this->respondWithError(500, "Unable to get reactions. " . $e->getMessage());
        }
    }

    public function insert()
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

        $inputReaction = $this->createObjectFromPostedJson("ReactionEntity");

        try {
            $reaction = $this->entityService->addReaction($inputReaction->getHtmlEntity(), false);
            $this->respond($reaction);
        } catch (Exception $e) {
            $this->respondWithError(500, "Unable to insert reaction. " . $e->getMessage());
        }
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

        $inputReaction = $this->createObjectFromPostedJson("ReactionEntity");

        try {
            $reaction = $this->entityService->editReaction($inputReaction->getId(), $inputReaction->getHtmlEntity(), $inputReaction->getIsNegativeOpinion());
            $this->respond($reaction);
        } catch (Exception $e) {
            $this->respondWithError(500, "Unable to update reaction. " . $e->getMessage());
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
            $this->entityService->deleteReaction($id);
            $this->respond(["message" => "Reaction deleted."]);
        } catch (Exception $e) {
            $this->respondWithError(500, "Unable to delete reaction. " . $e->getMessage());
        }
    }
}
