<?php

namespace LoremUserGenerator\App\Modules\AddMultipleUsers\RequestData;

use LoremUserGenerator\App\UserRole\UserRoleService;

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

    public static function isGenderValidOrCry(string $gender): void
    {
        if (!in_array($gender, ['male', 'female'])) {
            throw new \InvalidArgumentException('Unsupported gender: `'. $gender .'`');
        }
    }

    public static function isRoleValidOrCry(string $role): void
    {
        $usersRoles = UserRoleService::retrieveAllAvailableUserRoles();
        if (!array_key_exists($role, $usersRoles)) {
            throw new \InvalidArgumentException('User role not supported: `'. $role .'`');
        }
    }
}
