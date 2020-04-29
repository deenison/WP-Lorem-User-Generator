<?php

namespace LoremUserGenerator\App;

use LoremUserGenerator\App\Controller\NewUserController;

final class Bootstrap
{
    private function __construct()
    {
    }

    public static function start(): void
    {
//        add_action('admin_menu', array(self::class, 'registerAdminMenus'));
//        add_action('admin_init', array(self::class, 'registerSettings'));

        NewUserController::register();
    }

    public static function registerAdminMenus(): void
    {
        add_submenu_page(
            'users.php',
            'Lorem User Generator',
            'Lorem User Generator',
            'create_users',
            'lorem-user-generator',
            array(NewUserController::class, 'render')
        );
    }

    public static function registerSettings(): void
    {
        var_dump('registerSettings');
    }
}
