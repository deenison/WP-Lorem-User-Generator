<?php

namespace LoremUserGenerator\User;

final class UserEntityBuilder
{
    /** @var UserEntity */
    private $instance;
    /** @var callable */
    private $attributeSetter;

    /** @var string */
    private $firstName;
    /** @var string */
    private $lastName;
    /** @var string */
    private $email;
    /** @var string */
    private $username;
    /** @var string */
    private $password;

    public function __construct(UserEntity $instance, callable $attributeSetter)
    {
        $this->instance = $instance;
        $this->attributeSetter = $attributeSetter;
    }

    public function withFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function withLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function withEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function withUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function withPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function build(): UserEntity
    {
        $this->setProperty('firstName', $this->firstName);
        $this->setProperty('lastName', $this->lastName);
        $this->setProperty('email', $this->email);
        $this->setProperty('username', $this->username);
        $this->setProperty('password', $this->password);

        unset($this->attributeSetter);

        return $this->instance;
    }

    private function setProperty(string $attributeName, $attributeValue): void
    {
        if (!isset($this->attributeSetter)) {
            throw new \LogicException('An instance cannot be changed after being built');
        }

        $attributeSetter = $this->attributeSetter;
        $attributeSetter($attributeName, $attributeValue);
    }
}
