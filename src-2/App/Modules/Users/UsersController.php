<?php

namespace LoremUserGenerator\App\Modules\Users;

if (!defined('ABSPATH')) exit;

use LoremUserGenerator\App\Asset\AssetEnqueuer;

final class UsersController
{
    public static function register(): void
    {
        if (self::shouldLoadScripts()) {
            add_action('admin_enqueue_scripts', [self::class, 'registerScripts']);
        }
    }

    public static function registerScripts(): void
    {
        AssetEnqueuer::enqueueScript('lorem-user-generator', 'users.php');
        wp_localize_script(
            'lorem-user-generator',
            'LoremUserGenerator',
            [
                'anchor' => [
                    'href' => admin_url('users.php?page=lorem-user-generator-add-multiple-users'),
                    'label' => 'Add Multiple New',
                ],
            ]
        );
    }

    private static function shouldLoadScripts(): bool
    {
        return UsersPageValidator::isCurrentPage();
    }
}
