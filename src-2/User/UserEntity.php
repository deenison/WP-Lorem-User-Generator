<?php

namespace LoremUserGenerator\User;

final class UserEntity implements \JsonSerializable
{
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

    private function __construct()
    {
    }

    public static function builder(): UserEntityBuilder
    {
        $instance = new self();
        $attributeSetter = function (string $name, $value) use ($instance) {
            $instance->{$name} = $value;
        };

        return new UserEntityBuilder($instance, $attributeSetter);
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function jsonSerialize()
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password,
        ];
    }
}