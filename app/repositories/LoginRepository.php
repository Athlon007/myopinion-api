<?php

namespace Repositories;

use PDO;
use Models\Account;
use Models\AccountType;
use Repositories\AccountTypeRepository;

class LoginRepository extends Repository
{
    private $accountRepo;

    public function __construct()
    {
        parent::__construct();
        $this->accountRepo = new AccountTypeRepository();
    }

    private function accountsBuilder(array $arr): array
    {
        $output = array();
        foreach ($arr as $row) {
            $id = $row["id"];
            $username = $row["username"];
            $email = $row["email"];
            $passwordHash = $row["passwordHash"];
            $salt = $row["salt"];
            $accountType = $this->accountRepo->getById($row["accountType"]);

            $account = new Account();
            $account->setId($id);
            $account->setUsername($username);
            $account->setEmail($email);
            $account->setPasswordHash($passwordHash);
            $account->setSalt($salt);
            $account->setAccountType($accountType);

            array_push($output, $account);
        }

        return $output;
    }

    public function getRowsCount(): int
    {
        $stmt = $this->connection->prepare("SELECT COUNT(id) AS count FROM Accounts");
        $stmt->execute();
        return $stmt->fetch()["count"];
    }

    public function getRowsCountForEmail(string $email): int
    {
        $stmt = $this->connection->prepare("SELECT COUNT(id) AS count FROM Accounts WHERE email = :email OR username = :email;");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch()["count"];
    }

    public function getRowsCountForUsername(string $username): int
    {
        $stmt = $this->connection->prepare("SELECT COUNT(id) AS count FROM Accounts WHERE username = :username");
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch()["count"];
    }

    public function getRowsCountForId(int $id): int
    {
        $stmt = $this->connection->prepare("SELECT COUNT(id) AS count FROM Accounts WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()["count"];
    }

    public function insert(
        string $username,
        string $email,
        string $passwordHash,
        string $salt,
        AccountType $accountType
    ): int {
        $query = "INSERT INTO Accounts (username, email, passwordHash, salt, accountType) " .
            "VALUES (:username, :email, :passwordHash, :salt, :accountType)";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":passwordHash", $passwordHash, PDO::PARAM_STR);
        $stmt->bindParam(":salt", $salt, PDO::PARAM_STR);

        $accountTypeNumber = $accountType->getId();
        $stmt->bindParam(":accountType", $accountTypeNumber, PDO::PARAM_INT);
        $stmt->execute();

        return $this->connection->lastInsertId();
    }

    public function getAccountByEmailOrUsername(string $email): Account
    {
        $query = "SELECT id, username, email, passwordHash, salt, accountType FROM Accounts WHERE email = :email OR username = :email;";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $this->accountsBuilder($stmt->fetchAll())[0];
    }

    public function getAll(): array
    {
        $query = "SELECT id, username, email, passwordHash, salt, accountType FROM Accounts;";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $this->accountsBuilder($stmt->fetchAll());
    }

    public function getAccountById(int $id): ?Account
    {
        $query = "SELECT id, username, email, passwordHash, salt, accountType FROM Accounts WHERE id = :id;";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return null;
        }

        return $this->accountsBuilder($stmt->fetchAll())[0];
    }

    public function updateAccount(int $id, string $username, string $email, AccountType $accountType): void
    {
        $query = "UPDATE Accounts SET username = :username, email = :email, accountType = :accountType WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);

        $accountTypeNumber = $accountType->getId();
        $stmt->bindParam(":accountType", $accountTypeNumber, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePassword(int $id, string $passwordHash, string $salt): void
    {
        $sql = "UPDATE Accounts SET passwordHash = :passwordHash, salt = :salt WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(":passwordHash", $passwordHash, PDO::PARAM_STR);
        $stmt->bindParam(":salt", $salt, PDO::PARAM_STR);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function delete(int $id): void
    {
        $sql = "DELETE FROM Accounts WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
