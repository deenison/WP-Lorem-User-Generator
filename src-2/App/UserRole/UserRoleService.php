<?php

namespace LoremUserGenerator\App\UserRole;

if (!defined('ABSPATH')) exit;

final class UserRoleService
{
    private function __construct()
    {
    }

    public static function retrieveAllAvailableUserRoles(): array
    {
        $usersRoles = [];

        $editableRoles = get_editable_roles();
        foreach ($editableRoles as $userRoleSlug => $userRole) {
            $usersRoles[$userRoleSlug] = translate_user_role($userRole['name']);
        }
        unset($userRole, $userRoleSlug, $editableRoles);

        return $usersRoles;
    }
}
