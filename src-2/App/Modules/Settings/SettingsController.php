<?php

namespace LoremUserGenerator\App\Modules\Settings;

if (!defined('ABSPATH')) exit;

use LoremUserGenerator\App\Template\TemplateRenderer;
use LoremUserGenerator\App\UserRole\UserRoleService;
use LoremUserGenerator\Environment;

final class SettingsController
{
    public static function register(): void
    {
        add_action('admin_menu', [self::class, 'registerSidebarMenuItem']);
        add_action('admin_init', [self::class, 'registerPluginSettings']);
    }

    public static function registerPluginSettings(): void
    {
        register_setting(
            Environment::PLUGIN_SLUG,
            'lorem-user-generator:default_user_role',
            [
                'type' => 'string',
                'description' => __('User role that will be pre selected on the results table when generating dummy users data.', 'lorem-user-generator'),
                'default' => get_option('default_role'),
            ]
        );
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
        $actualDefaultRole = get_option('lorem-user-generator:default_user_role', '');
        $nonce = wp_create_nonce(Environment::PLUGIN_SLUG . '-options');

        $usersRoles = UserRoleService::retrieveAllAvailableUserRoles();

        $context = [
            'actualDefaultRole' => $actualDefaultRole,
            'nonce' => $nonce,
            'existentEditableUserRoles' => $usersRoles,
        ];

        TemplateRenderer::render('settings', $context);
    }
}
