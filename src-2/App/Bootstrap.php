<?php

namespace LoremUserGenerator\App;

use LoremUserGenerator\App\Modules\AddMultipleUsers\AddMultipleUsersController;
use LoremUserGenerator\App\Modules\AddSingleUser\NewUserController;
use LoremUserGenerator\App\Modules\Settings\SettingsController;
use LoremUserGenerator\App\Modules\Users\UsersController;

final class Bootstrap
{
    private function __construct()
    {
    }

    public static function start(): void
    {
//        add_action('admin_menu', array(self::class, 'registerAdminMenus'));
//        add_action('admin_init', array(self::class, 'registerSettings'));
        SettingsController::register();
        AddMultipleUsersController::register();
        NewUserController::register();
        UsersController::register();
    }

    public static function registerAdminMenus(): void
    {
        add_submenu_page(
            'users.php',
            'Lorem User Generator',
            'Lorem User Generator',
            'create_users',
            'lorem-user-generator',
            [NewUserController::class, 'render']
        );
    }
}
