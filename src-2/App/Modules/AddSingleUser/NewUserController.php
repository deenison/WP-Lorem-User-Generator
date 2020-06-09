<?php

namespace LoremUserGenerator\App\Modules\AddSingleUser;

if (!defined('ABSPATH')) exit;

use LoremUserGenerator\App\Asset\AssetEnqueuer;
use LoremUserGenerator\App\DataProvider\AppDataProviderService;
use LoremUserGenerator\App\Http\Response\HttpResponseDispatcher;
use LoremUserGenerator\App\Nonce\NewUserNonceService;

final class NewUserController
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

        add_action('wp_ajax_lorem_user_generator_fetch_random_data', [self::class, 'fetchRandomData']);
    }

    public static function registerScripts(): void
    {
        AssetEnqueuer::enqueueScript(self::NEW_USER_PAGE_IDENTIFIER, 'new-user-page');
        wp_localize_script(
            self::NEW_USER_PAGE_IDENTIFIER,
            'LoremUserGenerator',
            [
                'nonces' => [
                    'fetch_random_data' => NewUserNonceService::generateNonce(),
                ],
            ]
        );
    }

    public static function fetchRandomData(): void
    {
        if (!NewUserNonceService::isNonceInRequestValid()) {
            HttpResponseDispatcher::dispatchFailedResponse('Please, refresh your page and try again.');
        }

        $httpResponse = (new AppDataProviderService())->fetchRandomUser();

        HttpResponseDispatcher::dispatch($httpResponse);
    }

    private static function isPageSafeToLoadScripts(): bool
    {
        global $pagenow;
        return $pagenow === self::NEW_USER_PAGE_IDENTIFIER;
    }
}
