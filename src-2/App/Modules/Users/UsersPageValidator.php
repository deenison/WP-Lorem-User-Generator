<?php

namespace LoremUserGenerator\App\Modules\Users;

final class UsersPageValidator
{
    private const TARGET_PAGE = 'users.php';
    private const GET_PARAM_SUBPAGE = 'page';

    private function __construct()
    {
    }

    public static function isCurrentPage(): bool
    {
        if (!self::isPagenowTargetPage()) {
            return false;
        }

        $subPage = self::getSubpageFromRequest();
        return empty($subPage);
    }

    private static function isPagenowTargetPage(): bool
    {
        global $pagenow;
        return $pagenow === self::TARGET_PAGE;
    }

    private static function getSubpageFromRequest(): string
    {
        return $_GET[self::GET_PARAM_SUBPAGE] ?? '';
    }
}
