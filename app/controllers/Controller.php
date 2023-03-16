<?php

namespace Controllers;

use Exception;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Models;
use Time;
use Services\LoginService;
use Services\AccountTypeService;

class Controller
{
    protected function checkForJwt()
    {
        // Check for token header
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            return false;
        }

        // Read JWT from header
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        // Strip the part "Bearer " from the header
        $arr = explode(" ", $authHeader);
        $jwt = $arr[1];

        $loginService = new LoginService();

        if ($jwt) {
            try {
                $decoded = JWT::decode($jwt, new Key(SECRET, 'HS256'));

                // Check if user exists
                $user = $loginService->getUserById($decoded->data->id);

                if ($user == null) {
                    return false;
                }

                // check if expired
                $now = time();
                if ($now > $decoded->exp) {
                    return false;
                }

                return $decoded;
            } catch (Exception $e) {
                return false;
            }
        }
    }

    protected function respond($data)
    {
        $this->respondWithCode(200, ["data" => $data]);
    }

    protected function respondWithError($httpcode, $message)
    {
        $data = array('errorMessage' => $message);
        $this->respondWithCode($httpcode, $data);
    }

    private function respondWithCode($httpcode, $data)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($httpcode);
        echo json_encode($data);
    }

    protected function createObjectFromPostedJson($className, $useBasenameIdValue = false)
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        // set namespace to Models
        $className = "Models\\" . $className;

        $object = new $className();
        foreach ($data as $key => $value) {
            if (is_object($value)) {
                continue;
            }

            if ($useBasenameIdValue && $key == "id") {
                $value = basename($_SERVER['REQUEST_URI']);
            }

            $methodName = "set" . ucfirst($key);

            $object->$methodName($value);
        }

        return $object;
    }

    protected function checkIfTokenHolderIsAdmin($jwt)
    {
        $username = $jwt->data->username;

        $service = new LoginService();
        $user = $service->getUserByEmail($username);

        $accountTypeService = new AccountTypeService();
        $adminType = $accountTypeService->getAccountTypeById(1);

        if ($user->getAccountType()->getId() != $adminType->getId()) {
            return false;
        }

        return true;
    }

    protected function checkIfTokenHolderIsModerator($jwt)
    {
        $username = $jwt->data->username;

        $service = new LoginService();
        $user = $service->getUserByEmail($username);

        $accountTypeService = new AccountTypeService();
        $adminType = $accountTypeService->getAccountTypeById(2);

        if ($user->getAccountType()->getId() != $adminType->getId()) {
            return false;
        }

        return true;
    }
}
