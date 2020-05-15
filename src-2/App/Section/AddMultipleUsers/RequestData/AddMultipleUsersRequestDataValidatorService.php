<?php

namespace LoremUserGenerator\App\Section\AddMultipleUsers\RequestData;

final class AddMultipleUsersRequestDataValidatorService
{
    private function __construct()
    {
    }

    public static function isQuantityValidOrCry(int $quantity): void
    {
        if ($quantity < 0 || $quantity > 25) {
            throw new \InvalidArgumentException('Please, provide a `quantity` which is between 1 and 25.');
        }
    }
}
