<?php

namespace LoremUserGenerator\App\Controller;

final class SettingsController
{
    public static function register(): void
    {
        add_action('admin_menu', [self::class, 'registerSidebarMenuItem']);
    }

    public static function registerSidebarMenuItem(): void
    {
        add_options_page(
            'Lorem User Generator Settings',
            'Lorem User Generator',
            'manage_options',
            'lorem-user-generator_settings',
            array(self::class, 'renderSettingsPageView')
        );
    }

    public static function renderSettingsPageView(): void
    {

    }
}
