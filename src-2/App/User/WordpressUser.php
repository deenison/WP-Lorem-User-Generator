<?php

namespace LoremUserGenerator\App\User;

use LoremUserGenerator\User\UserEntity;
use LoremUserGenerator\User\UserEntityInterface;

final class WordpressUser implements UserEntityInterface
{
    /** @var UserEntity */
    private $user;

    private function __construct(UserEntity $user)
    {
        $this->user = $user;
    }

    public static function fromUser(UserEntity $user): self
    {
        return new self($user);
    }

    public function getFirstName(): string
    {
        return $this->user->getFirstName();
    }

    public function getLastName(): string
    {
        return $this->user->getLastName();
    }

    public function getEmail(): string
    {
        return $this->user->getEmail();
    }

    public function getUsername(): string
    {
        return $this->user->getUsername();
    }

    public function getPassword(): string
    {
        return $this->user->getPassword();
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->user->getFirstName(),
            'last_name' => $this->user->getLastName(),
            'user_email' => $this->user->getEmail(),
            'user_login' => $this->user->getUsername(),
            'user_pass' => $this->user->getPassword(),
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
