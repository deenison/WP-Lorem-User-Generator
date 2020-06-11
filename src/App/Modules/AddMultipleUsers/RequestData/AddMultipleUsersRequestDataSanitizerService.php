<?php

namespace LoremUserGenerator\App\Modules\AddMultipleUsers\RequestData;

if (!defined('ABSPATH')) exit;

final class AddMultipleUsersRequestDataSanitizerService
{
    private function __construct()
    {
    }

    public static function sanitizeString(string $subject): string
    {
        return filter_var($subject, FILTER_SANITIZE_STRING);
    }
}
