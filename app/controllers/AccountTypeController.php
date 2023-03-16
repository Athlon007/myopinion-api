<?php

namespace Controllers;

use Services\AccountTypeService;

class AccountTypeController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new AccountTypeService();
    }

    public function getAll()
    {
        $accountTypes = $this->service->getAll();
        $this->respond($accountTypes);
    }

    public function getById()
    {
        $id = basename($_SERVER['REQUEST_URI']);
        $accountType = $this->service->getAccountTypeById($id);

        $this->respond($accountType);
    }

    public function insert()
    {
        $token = $this->checkForJwt();
        if (!$token) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        // check if admin
        if (!$this->checkIfTokenHolderIsAdmin($token)) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        $inserted = $this->createObjectFromPostedJson("AccountType");
        $accountType = $this->service->insert($inserted);

        $this->respond($accountType);
    }

    public function update()
    {
        $token = $this->checkForJwt();
        if (!$token) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        // check if admin
        if (!$this->checkIfTokenHolderIsAdmin($token)) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        $updated = $this->createObjectFromPostedJson("AccountType");
        $accountType = $this->service->update($updated);

        $this->respond($accountType);
    }

    public function delete()
    {
        $token = $this->checkForJwt();
        if (!$token) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        // check if admin
        if (!$this->checkIfTokenHolderIsAdmin($token)) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        $id = basename($_SERVER['REQUEST_URI']);
        $this->service->delete($id);

        $this->respond("Account type deleted");
    }
}
