<?php

namespace LoremUserGenerator\App\Modules\Settings;

if (!defined('ABSPATH')) exit;

use LoremUserGenerator\App\Template\TemplateRenderer;

final class SettingsController
{
    public static function register(): void
    {
        add_action('admin_menu', [self::class, 'registerSidebarMenuItem']);
    }

    public static function registerSidebarMenuItem(): void
    {
        add_options_page(
            __('Lorem User Generator Settings', 'lorem-user-generator'),
            __('Lorem User Generator', 'lorem-user-generator'),
            'manage_options',
            'lorem-user-generator',
            [self::class, 'renderSettingsPageView']
        );
    }

    public static function renderSettingsPageView(): void
    {
        TemplateRenderer::render('settings');
    }
}
