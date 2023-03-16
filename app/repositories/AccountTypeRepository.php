<?php

namespace Repositories;

use Models\AccountType;
use PDO;

class AccountTypeRepository extends Repository
{
    private function buildAccountTypes($arr): array
    {
        $accountTypes = [];
        foreach ($arr as $row) {
            $accountType = new AccountType();
            $accountType->setId($row["id"]);
            $accountType->setName($row["name"]);
            $accountTypes[] = $accountType;
        }
        return $accountTypes;
    }

    public function getAll()
    {
        $stmt = $this->connection->prepare("SELECT id, name FROM AccountTypes");
        $stmt->execute();
        return $this->buildAccountTypes($stmt->fetchAll());
    }

    public function getById($id): ?AccountType
    {
        $stmt = $this->connection->prepare("SELECT id, name FROM AccountTypes WHERE id = :id");
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $accountTypes = $this->buildAccountTypes($stmt->fetchAll());
        if (empty($accountTypes)) {
            return null;
        }
        return $accountTypes[0];
    }

    public function insert(AccountType $accountType): int
    {
        $query = "INSERT INTO AccountTypes (name) VALUES (:name)";
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(":name", $accountType->getName(), PDO::PARAM_STR);
        $stmt->execute();

        return $this->connection->lastInsertId();
    }

    public function update(AccountType $accountType)
    {
        $query = "UPDATE AccountTypes SET name = :name WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(":name", $accountType->getName(), PDO::PARAM_STR);
        $stmt->bindValue(":id", $accountType->getId(), PDO::PARAM_INT);
        $stmt->execute();
    }

    public function delete($id)
    {
        $query = "DELETE FROM AccountTypes WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
