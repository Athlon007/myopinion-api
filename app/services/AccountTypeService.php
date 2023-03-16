<?php

namespace Services;

use Models\AccountType;
use Models\Exceptions\IllegalOperationException;
use Repositories\AccountTypeRepository;

class AccountTypeService
{
    private AccountTypeRepository $repo;

    public function __construct()
    {
        $this->repo = new AccountTypeRepository();
    }

    public function getAll(): array
    {
        return $this->repo->getAll();
    }

    public function getAccountTypeById(int $id): ?AccountType
    {
        $id = htmlspecialchars($id);
        return $this->repo->getById($id);
    }

    public function insert(AccountType $accountType): AccountType
    {
        $accountType->setId(htmlspecialchars($accountType->getId()));
        $accountType->setName(htmlspecialchars($accountType->getName()));

        $id = $this->repo->insert($accountType);
        return $this->repo->getById($id);
    }

    public function update(AccountType $accountType)
    {
        $accountType->setId(htmlspecialchars($accountType->getId()));
        $accountType->setName(htmlspecialchars($accountType->getName()));

        $this->repo->update($accountType);

        return $this->repo->getById($accountType->getId());
    }

    public function delete(int $id)
    {
        if ($id == 1 || $id == 2) {
            throw new IllegalOperationException("Cannot delete default account types!");
        }

        $id = htmlspecialchars($id);
        $this->repo->delete($id);
    }
}
