<?php

namespace LoremUserGenerator\App\User;

use LoremUserGenerator\User\UserEntity;
use LoremUserGenerator\User\UserEntityInterface;

final class WordpressUser implements UserEntityInterface
{
    /** @var UserEntity */
    private $user;

    /** @var string */
    private $role;

    private function __construct(UserEntity $user, string $userRole)
    {
        $this->user = $user;
        $this->role = $userRole;
    }

    public static function fromUser(UserEntity $user, string $userRole): self
    {
        return new self($user, $userRole);
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

    public function getRole(): string
    {
        return $this->role;
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->user->getFirstName(),
            'last_name' => $this->user->getLastName(),
            'user_email' => $this->user->getEmail(),
            'user_login' => $this->user->getUsername(),
            'user_pass' => $this->user->getPassword(),
            'role' => $this->role,
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
