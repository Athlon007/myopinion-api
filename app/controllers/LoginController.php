<?php

namespace Controllers;

use Services\LoginService;
use Services\AccountTypeService;

use \Firebase\JWT\JWT;

use \Models\Exceptions\AccountNotFoundException;
use TypeError;

class LoginController extends Controller
{
    private LoginService $service;
    const EXPIRE_TIME = 60000;

    public function __construct()
    {
        $this->service = new LoginService();
    }

    public function login()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        $username = $data->username;
        $password = $data->password;

        try {
            $user = $this->service->login($username, $password);

            if ($user == null) {
                $this->respondWithError(401, "Invalid username or password.");
            } else {
                $this->respond($this->generateJwt($user));
            }
        } catch (AccountNotFoundException $e) {
            $this->respondWithError(401, $e->getMessage());
        } catch (\Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        } catch (TypeError $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function generateJwt($user)
    {
        $issuer = "THE_ISSUER"; // this can be the domain/servername that issues the token
        $audience = "THE_AUDIENCE"; // this can be the domain/servername that checks the token

        $issuedAt = time(); // issued at
        $notbefore = $issuedAt; //not valid before
        $expire = $issuedAt + LoginController::EXPIRE_TIME; // expire in 1 hour

        $payload = array(
            "iss" => $issuer,
            "aud" => $audience,
            "iat" => $issuedAt,
            "nbf" => $notbefore,
            "exp" => $expire,
            "data" => array(
                "id" => $user->getID(),
                "username" => $user->getUsername(),
            )
        );

        $jwt = JWT::encode($payload, SECRET, 'HS256');

        return
            array(
                "message" => "Successful login.",
                "jwt" => $jwt,
                "username" => $user->getUsername(),
                "expireAt" => $expire
            );
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

        $users = $this->service->getAll();
        $this->respond($users);
    }

    public function getById()
    {
        $token = $this->checkForJwt();
        if (!$token) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        $id = basename($_SERVER['REQUEST_URI']);

        // Check if user is admin or if user is the same as the one in the token
        if (!$this->checkIfTokenHolderIsAdmin($token) && $token->data->id != $id) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        $user = $this->service->getUserById($id);
        $this->respond($user);
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

        $json = file_get_contents('php://input');
        $data = json_decode($json);

        $email = $data->email;
        $username = $data->username;
        $password = $data->password;

        $accountTypeService = new AccountTypeService();
        $accountType = $accountTypeService->getAccountTypeById($data->accountType->id);

        $user = $this->service->createAccount($username, $email, $password, $accountType);
        $this->respond($user);
    }

    public function update()
    {
        $token = $this->checkForJwt();
        if (!$token) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        $id = basename($_SERVER['REQUEST_URI']);

        // Check if user is admin or if user is the same as the one in the token
        if (!$this->checkIfTokenHolderIsAdmin($token) && $token->data->id != $id) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        $json = file_get_contents('php://input');
        $data = json_decode($json);

        $loginService = new LoginService();
        $accountTypeService = new AccountTypeService();

        $id = basename($_SERVER['REQUEST_URI']);
        $username = $data->username;
        $email = $data->email;
        $password = $data->password;
        $accountType = $accountTypeService->getAccountTypeById($data->accountType->id);

        $login = $loginService->getUserById($id);
        // Only admin can update account types.
        if (!$this->checkIfTokenHolderIsAdmin($token) && $login->getAccountType()->getId() != $accountType->getId()) {
            $accountType = $login->getAccountType();
        }

        $account = $this->service->updateAccount($id, $username, $email, $password, $accountType);
        $this->respond($account);
    }

    public function delete()
    {
        $token = $this->checkForJwt();
        if (!$token) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        $id = basename($_SERVER['REQUEST_URI']);

        // Check if user is admin or if user is the same as the one in the token
        if (!$this->checkIfTokenHolderIsAdmin($token) && $token->data->id != $id) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        $this->service->deleteAccount($id);
        $this->respond(array("message" => "Account deleted."));
    }

    public function getMe()
    {
        $token = $this->checkForJwt();
        if (!$token) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        $user = $this->service->getUserById($token->data->id);
        $this->respond($user);
    }
}
