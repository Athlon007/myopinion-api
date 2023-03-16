<?php

namespace Services;

use DateTime;
use Models\Account;
use Models\AccountType;
use Models\Exceptions\AccountNotFoundException;
use Models\Exceptions\LoginCreationException;
use Models\Exceptions\IllegalOperationException;
use Repositories\LoginRepository;

class LoginService
{
    private LoginRepository $repo;
    public const SALT_LENGTH = 64;
    public const MAX_MINUTES_LOGGED_IN = 60;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->repo = new LoginRepository();
    }


    // Returns true, if one (or more) accounts exists.
    public function isSetup(): bool
    {
        return $this->repo->getRowsCount() > 0;
    }

    public function isPasswordValid($password): bool
    {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        return $uppercase && $lowercase && $number && $specialChars;
    }

    public function doPasswordsMatch($password, $confirmation): bool
    {
        return $password === $confirmation;
    }

    /**
     * Creates a new account.
     */
    public function createAccount(string $username, string $email, string $password, AccountType $accountType): Account
    {
        $errors = "";
        if ($this->doesUsernameExist($username)) {
            $errors .= "Provided username is already in use.<br>";
        }
        if ($this->doesEmailExist($email)) {
            $errors .= "Provided e-mail is alreay in use.";
        }

        if (strlen($errors) > 0) {
            throw new LoginCreationException($errors);
        }

        $username = htmlspecialchars($username);
        $email = htmlspecialchars($email);
        $password = htmlspecialchars($password);
        $salt = htmlspecialchars($this->generateSalt());
        $hash = htmlspecialchars($this->generatePasswordHash($password, $salt));

        $id = $this->repo->insert($username, $email, $hash, $salt, $accountType);
        return $this->getUserById($id);
    }

    /**
     * Generates salt.
     * @return string New salt.
     */
    private function generateSalt(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+{}[]"|<>?,./`~';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < LoginService::SALT_LENGTH; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Generates a new password hash using bcrypt.
     * @param string $password Password inputted by the user.
     * @param string $salt Salt that will be added to the password.
     * @return string New password hash.
     */
    private function generatePasswordHash($password, $salt): string
    {
        $options = ['cost' => 11];
        return password_hash($password . $salt, PASSWORD_BCRYPT, $options);
    }

    /**
     * Checks if username exists.
     * @param string $username Username to check for.
     * @return bool True, if username exists. False, if not.
     */
    private function doesUsernameExist(string $username): bool
    {
        $username = htmlspecialchars($username);
        return $this->repo->getRowsCountForUsername($username) > 0;
    }

    /**
     * Checks if email exists.
     * @param string $email Email to look for.
     * @return bool True, if email exists in database, false if not.
     */
    private function doesEmailExist(string $email): bool
    {
        $email = htmlspecialchars($email);
        return $this->repo->getRowsCountForEmail($email) > 0;
    }

    /**
     * Returns an account by its email address.
     * @param string $email Email with which user should be found.
     * @return Account account object.
     * @throws AccountNotFoundException Thrown, if the account with provided e-mail does not exist.
     */
    public function getUserByEmail(string $email): Account
    {
        $email = htmlspecialchars($email);

        if (!$this->doesEmailExist($email)) {
            throw new AccountNotFoundException("An e-mail, and/or password do not match.");
        }

        return $this->repo->getAccountByEmailOrUsername($email);
    }

    /**
     * Get the user, if email and passwords match.
     * @param string $email Email to find the account by.
     * @param string $password Password of the account.
     * @return Account Returns an account, if email and passwords match.
     * @throws AccountNotFoundException Thrown, if the account with provided email does not exist.
     */
    public function login(string $email, string $password): Account
    {
        // Delay to prevent brute force attacks
        sleep(2);

        $email = htmlspecialchars($email);
        $password = htmlspecialchars($password);
        $account = $this->getUserByEmail($email);
        if (!$this->verifyPassword($password, $account->getSalt(), $account->getPasswordHash())) {
            throw new AccountNotFoundException("An e-mail, and/or password do not match.");
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return $account;
    }

    public function getUserById(int $id): ?Account
    {
        $id = htmlspecialchars($id);

        return $this->repo->getAccountById($id);
    }

    /**
     * Verifies the password.
     * @param string $password Password to check.
     * @param string $salt Account salt.
     * @param string $hash Password hash (stored in the database).
     * @return bool Returns true, if password match.
     */
    private function verifyPassword($password, $salt, $hash): bool
    {
        return password_verify($password . $salt, $hash);
    }


    /**
     * Returns all users.
     * @return Array Array of all Accounts.
     */
    public function getAll(): array
    {
        return $this->repo->getAll();
    }

    /**
     * Updates the account details (except for the password).
     */
    public function editAccount(int $id, string $username, string $email, AccountType $type): void
    {
        $id = htmlspecialchars($id);
        $username = htmlspecialchars($username);
        $email = htmlspecialchars($email);

        $this->repo->updateAccount($id, $username, $email, $type);
    }

    /**
     * Updates the password of an account.
     */
    public function updatePassword(int $id, string $password): void
    {
        if (!$this->isPasswordValid($password)) {
            throw new IllegalOperationException("Password does not meet the criteria!");
        }

        $id = htmlspecialchars($id);
        $password = htmlspecialchars($password);
        $salt = htmlspecialchars($this->generateSalt());
        $hash = htmlspecialchars($this->generatePasswordHash($password, $salt));
        $this->repo->updatePassword($id, $hash, $salt);
    }

    /**
     * Removes the account by ID.
     */
    public function deleteAccount(int $id): void
    {
        $id = htmlspecialchars($id);
        $this->repo->delete($id);
    }

    public function updateAccount($id, $username, $email, $password, AccountType $accountType): Account
    {
        $id = htmlspecialchars($id);
        $username = htmlspecialchars($username);
        $email = htmlspecialchars($email);
        $password = htmlspecialchars($password);
        $accountType->setId(htmlspecialchars($accountType->getId()));
        $accountType->setName(htmlspecialchars($accountType->getName()));

        $this->repo->updateAccount(
            $id,
            $username,
            $email,
            $accountType
        );

        $this->updatePassword($id, $password);

        return $this->getUserById($id);
    }
}
