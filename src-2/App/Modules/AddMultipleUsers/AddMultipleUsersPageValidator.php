<?php

namespace LoremUserGenerator\App\Modules\AddMultipleUsers;

if (!defined('ABSPATH')) exit;

final class AddMultipleUsersPageValidator
{
    private const NEW_USERS_PAGE_NAME = 'users.php';

    private function __construct()
    {
    }

    public static function isCurrentPage(): bool
    {
        global $pagenow;
        return $pagenow === self::NEW_USERS_PAGE_NAME;
    }
}
