<?php

namespace LoremUserGenerator\App\Modules\AddMultipleUsers\RequestData;

use LoremUserGenerator\App\UserRole\UserRoleService;

final class AddMultipleUsersRequestDataValidatorService
{
    private function __construct()
    {
    }

    public static function isRoleValidOrCry(string $role): void
    {
        $usersRoles = UserRoleService::retrieveAllAvailableUserRoles();
        if (!array_key_exists($role, $usersRoles)) {
            throw new \InvalidArgumentException('User role not supported: `'. $role .'`');
        }
    }
}
