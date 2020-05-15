<?php

namespace LoremUserGenerator\App\Section\AddMultipleUsers;

use LoremUserGenerator\App\Asset\AssetEnqueuer;
use LoremUserGenerator\App\DataProvider\AppDataProviderService;
use LoremUserGenerator\App\Http\Response\HttpResponseDispatcher;
use LoremUserGenerator\App\Nonce\NewUsersNonceService;
use LoremUserGenerator\App\Section\AddMultipleUsers\RequestData\AddMultipleUsersRequestDataRetrieverService;
use LoremUserGenerator\App\Template\TemplateRenderer;

final class AddMultipleUsersController
{
    private const NEW_USER_PAGE_IDENTIFIER = 'user-new.php';

    private function __construct()
    {
    }

    public static function register(): void
    {
        if (AddMultipleUsersPageValidator::isCurrentPage()) {
            add_action('admin_enqueue_scripts', [self::class, 'registerScripts']);
        }

        add_action('wp_ajax_lorem_user_generator_fetch_multiple_random_data', [self::class, 'fetchRandomData']);

        add_action('admin_menu', [self::class, 'registerSidebarMenuItem']);
    }

    public static function registerSidebarMenuItem(): void
    {
        add_submenu_page(
            'users.php',
            'Add Multiple New Users',
            'Add Multiple New',
            'create_users',
            'lorem-user-generator-add-multiple-users',
            [self::class, 'renderPageTemplate'],
            2
        );
    }

    public static function renderPageTemplate(): void
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

        try {
            $options = self::buildRequestOptionsArrayOrCry();
        } catch (\InvalidArgumentException $exception) {
            HttpResponseDispatcher::dispatchFailedResponse($exception->getMessage());
        } catch (\Throwable $exception) {
            HttpResponseDispatcher::dispatchErrorResponse($exception->getMessage());
        }

        $httpResponse = (new AppDataProviderService())->fetchRandomUser($options);
        HttpResponseDispatcher::dispatch($httpResponse);
    }

    private static function buildRequestOptionsArrayOrCry(): array
    {
        return [
            'results' => AddMultipleUsersRequestDataRetrieverService::retrieveQuantity(),
        ];
    }
}
