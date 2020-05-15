<?php

namespace LoremUserGenerator\App\Section\AddMultipleUsers;

use LoremUserGenerator\App\Asset\AssetEnqueuer;
use LoremUserGenerator\App\DataProvider\AppDataProviderService;
use LoremUserGenerator\App\Http\Response\ErrorHttpResponse;
use LoremUserGenerator\App\Http\Response\HttpResponseDispatcher;
use LoremUserGenerator\App\Nonce\NewUsersNonceService;
use LoremUserGenerator\App\Template\TemplateRenderer;

final class AddMultipleUsersController
{
    private const NEW_USER_PAGE_IDENTIFIER = 'user-new.php';

    private function __construct()
    {
    }

    public static function register(): void
    {
        if (self::isPageSafeToLoadScripts()) {
            add_action('admin_enqueue_scripts', [self::class, 'registerScripts']);
        }

        add_action('wp_ajax_lorem_user_generator_fetch_multiple_random_data', [self::class, 'fetchRandomData']);

        add_action('admin_menu', [self::class, 'foo']);
    }

    public static function foo(): void
    {
        add_submenu_page(
            'users.php',
            'Add Multiple New Users',
            'Add Multiple New',
            'create_users',
            'lorem-user-generator-add-multiple-users',
            [self::class, 'lorem'],
            2
        );
    }

    public static function lorem(): void
    {
        TemplateRenderer::render(
            'add-multiple-new'
        );
    }

    public static function registerScripts(): void
    {
        AssetEnqueuer::enqueueScript(self::NEW_USER_PAGE_IDENTIFIER, 'add-multiple-users');
        wp_localize_script(
            self::NEW_USER_PAGE_IDENTIFIER,
            'LoremUserGenerator',
            [
                'nonce' => NewUsersNonceService::generateNonce(),
            ]
        );
    }

    public static function fetchRandomData(): void
    {
        if (!NewUsersNonceService::isNonceInRequestValid()) {
            HttpResponseDispatcher::dispatchFailedResponse('Please, refresh your page and try again.');
        }

        $qty = (int)filter_var($_GET['qty'] ?? 1, FILTER_SANITIZE_NUMBER_INT);
        if ($qty < 0 || $qty > 25) {
            $httpResponse = new ErrorHttpResponse('Please, provide a `quantity` which is between 1 and 25.');
            HttpResponseDispatcher::dispatch($httpResponse);
        }

        $options = [
            'results' => $qty,
        ];

        $httpResponse = (new AppDataProviderService())->fetchRandomUser($options);

        HttpResponseDispatcher::dispatch($httpResponse);
    }

    private static function isPageSafeToLoadScripts(): bool
    {
        global $pagenow;
        return $pagenow === 'users.php';
    }
}
