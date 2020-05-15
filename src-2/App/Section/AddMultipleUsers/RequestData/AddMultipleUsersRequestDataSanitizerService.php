<?php

namespace LoremUserGenerator\App\Section\AddMultipleUsers\RequestData;

final class AddMultipleUsersRequestDataSanitizerService
{
    private function __construct()
    {
    }

    public static function sanitizeQuantity(string $quantity): int
    {
        return (int)$quantity;
    }
}
