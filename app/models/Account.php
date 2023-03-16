<?php

namespace Models;

use JsonSerializable;

class Account implements JsonSerializable
{
    private int $id;
    private string $username;
    private string $email;
    private string $passwordHash;
    private string $salt;
    private AccountType $accountType;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $value): void
    {
        $this->username = $value;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $value): void
    {
        $this->email = $value;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $value): void
    {
        $this->passwordHash = $value;
    }

    public function getSalt(): string
    {
        return $this->salt;
    }

    public function setSalt(string $value): void
    {
        $this->salt = $value;
    }

    public function getAccountType(): AccountType
    {
        return $this->accountType;
    }

    public function setAccountType(AccountType $value): void
    {
        $this->accountType = $value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "id" => $this->getId(),
            "username" => $this->getUsername(),
            "email" => $this->getUsername(),
            "passwordHash" => $this->getPasswordHash(),
            "accountType" => $this->getAccountType()
        ];
    }
}
