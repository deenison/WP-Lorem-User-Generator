<?php

namespace LoremUserGenerator\User;

if (!defined('ABSPATH')) exit;

interface UserEntityInterface extends \JsonSerializable
{
    public function toArray(): array;
}
