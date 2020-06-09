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
        SettingsController::register();
        AddMultipleUsersController::register();
        NewUserController::register();
        UsersController::register();
    }
}
