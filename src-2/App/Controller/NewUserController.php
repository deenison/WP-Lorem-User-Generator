<?php

namespace LoremUserGenerator\App\Controller;

use LoremUserGenerator\App\Asset\AssetEnqueuer;
use LoremUserGenerator\App\Http\Response\ErrorHttpResponse;
use LoremUserGenerator\App\Http\Response\FailedHttpResponse;
use LoremUserGenerator\App\Http\Response\SuccessfulHttpResponse;
use LoremUserGenerator\App\Nonce\NewUserNonceService;
use LoremUserGenerator\DataProvider\Exception\DataProviderException;
use LoremUserGenerator\Http\HttpClientService;
use LoremUserGenerator\LoremUserGeneratorFacade;
use LoremUserGenerator\User\UserEntity;
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
        header('Content-Type: application/json');

        if (!NewUserNonceService::isNonceInRequestValid()) {
            self::finishWithFailedJsonResponse('Please, refresh your page and try again.');
        }

        $httpClient = (new HttpClientService())->getHttpClient();
        $app = new LoremUserGeneratorFacade($httpClient);

        try {
            $user = $app->fetchUserWithRandomData();
        } catch (ClientExceptionInterface $exception) {
            self::finishWithFailedJsonResponse($exception->getMessage());
        } catch (DataProviderException | \Throwable $exception) {
            $errorMessage = preg_match('/\s+time[d]?\s+out/i', $exception->getMessage())
                ? 'The request has timed out.'
                : $exception->getMessage();
            self::finishWithErrorJsonResponse($errorMessage);
        }

        self::finishWithSuccessfulJsonResponse($user);
    }

    private static function finishWithSuccessfulJsonResponse(UserEntity $user): void
    {
        $successfulHttpResponse = new SuccessfulHttpResponse($user);
        echo json_encode($successfulHttpResponse);
        wp_die();
    }

    private static function finishWithFailedJsonResponse(string $errorMessage): void
    {
        $failedHttpResponse = new FailedHttpResponse($errorMessage);
        echo json_encode($failedHttpResponse);
        wp_die();
    }

    private static function finishWithErrorJsonResponse(string $errorMessage): void
    {
        $errorHttpResponse = new ErrorHttpResponse($errorMessage);
        echo json_encode($errorHttpResponse);
        wp_die();
    }

    private static function isPageSafeToLoadScripts(): bool
    {
        global $pagenow;
        return $pagenow === self::NEW_USER_PAGE_IDENTIFIER;
    }
}
