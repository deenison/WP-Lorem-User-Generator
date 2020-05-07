<?php

namespace LoremUserGenerator\App\Controller;

use LoremUserGenerator\App\Asset\AssetEnqueuer;
use LoremUserGenerator\App\Http\Response\HttpResponseDispatcher;
use LoremUserGenerator\App\Nonce\NewUserNonceService;
use LoremUserGenerator\DataProvider\Exception\DataProviderException;
use LoremUserGenerator\Http\HttpClientService;
use LoremUserGenerator\LoremUserGeneratorFacade;
use Psr\Http\Client\ClientExceptionInterface;

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

        $httpClient = (new HttpClientService())->getHttpClient();
        $app = new LoremUserGeneratorFacade($httpClient);

        try {
            $user = $app->fetchUserWithRandomData();
        } catch (ClientExceptionInterface $exception) {
            HttpResponseDispatcher::dispatchFailedResponse($exception->getMessage());
        } catch (DataProviderException | \Throwable $exception) {
            $errorMessage = preg_match('/\s+time[d]?\s+out/i', $exception->getMessage())
                ? 'The request has timed out.'
                : $exception->getMessage();
            HttpResponseDispatcher::dispatchErrorResponse($errorMessage);
        }

        HttpResponseDispatcher::dispatchSuccessfulResponse($user);
    }

    private static function isPageSafeToLoadScripts(): bool
    {
        global $pagenow;
        return $pagenow === self::NEW_USER_PAGE_IDENTIFIER;
    }
}
