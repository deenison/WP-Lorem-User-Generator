<?php

namespace LoremUserGenerator\App\Modules\AddMultipleUsers\RequestData;

final class AddMultipleUsersRequestDataSanitizerService
{
    private function __construct()
    {
    }

    public static function sanitizeQuantity(string $quantity): int
    {
        return (int)$quantity;
    }

    public static function sanitizeString(string $subject): string
    {
        return filter_var($subject, FILTER_SANITIZE_STRING);
    }
}
