<?php

namespace Controllers;

use Services\LoginService;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

use \Models\Exceptions\AccountNotFoundException;

class LoginController extends Controller
{
    private LoginService $service;

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
        }
    }

    public function generateJwt($user)
    {
        $issuer = "THE_ISSUER"; // this can be the domain/servername that issues the token
        $audience = "THE_AUDIENCE"; // this can be the domain/servername that checks the token

        $issuedAt = time(); // issued at
        $notbefore = $issuedAt; //not valid before
        $expire = $issuedAt + 3600; // expire in 1 hour

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
}
