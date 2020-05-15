<?php

namespace LoremUserGenerator\App\Section\AddMultipleUsers;

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
