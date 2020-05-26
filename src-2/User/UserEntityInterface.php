<?php

namespace LoremUserGenerator\User;

interface UserEntityInterface extends \JsonSerializable
{
    public function toArray(): array;
}
