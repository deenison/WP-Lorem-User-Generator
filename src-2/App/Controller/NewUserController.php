<?php

namespace LoremUserGenerator\App\Controller;

use LoremUserGenerator\App\Asset\AssetEnqueuer;
use LoremUserGenerator\Core\LoremUserGeneratorFacade;
use LoremUserGenerator\Http\GuzzleHttpClientBuilder;

final class NewUserController
{
    private const NEW_USER_PAGE_IDENTIFIER = 'user-new.php';

    private function __construct()
    {
    }

    public static function register(): void
    {
        if (self::isPageSafeToLoadScripts()) {
            add_action('admin_enqueue_scripts', array(self::class, 'registerScripts'));
        }

        add_action('wp_ajax_lorem_user_generator_fetch_random_data', array(self::class, 'fetchRandomData'));
    }

    public static function registerScripts(): void
    {
        AssetEnqueuer::enqueueScript(self::NEW_USER_PAGE_IDENTIFIER, 'new-user-page');
        wp_localize_script(
            self::NEW_USER_PAGE_IDENTIFIER,
            'LoremUserGenerator',
            [
                'nonces' => [
                    'fetch_random_data' => wp_create_nonce('lorem_user_generator_fetch_random_data'),
                ],
            ]
        );
    }

    public static function fetchRandomData(): void
    {
        header('Content-Type: application/json');

        $httpClient = (new GuzzleHttpClientBuilder())->build();
        $app = new LoremUserGeneratorFacade($httpClient);

        $user = $app->fetchUserWithRandomData();

        $responsePayload = [
            'status' => 'success',
            'data' => $user,
        ];
        echo json_encode($responsePayload);

        wp_die();
    }

    private static function isPageSafeToLoadScripts(): bool
    {
        global $pagenow;
        return $pagenow === self::NEW_USER_PAGE_IDENTIFIER;
    }
}
