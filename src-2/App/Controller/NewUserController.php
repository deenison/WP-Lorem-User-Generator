<?php

namespace LoremUserGenerator\App\Controller;

use LoremUserGenerator\App\Asset\AssetEnqueuer;
use LoremUserGenerator\Core\LoremUserGeneratorFacade;
use LoremUserGenerator\Core\User\UserEntity;
use LoremUserGenerator\DataProvider\Exception\DataProviderException;
use LoremUserGenerator\Http\Adapter\Guzzle\GuzzleHttpClientBuilder;
use LoremUserGenerator\Http\HttpClientService;
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
                    'fetch_random_data' => wp_create_nonce('lorem_user_generator_fetch_random_data'),
                ],
            ]
        );
    }

    public static function fetchRandomData(): void
    {
        header('Content-Type: application/json');

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
        echo json_encode([
            'status' => 'success',
            'data' => $user,
        ]);
        wp_die();
    }

    private static function finishWithFailedJsonResponse(string $errorMessage): void
    {
        echo json_encode([
            'status' => 'fail',
            'error' => $errorMessage,
        ]);
        wp_die();
    }

    private static function finishWithErrorJsonResponse(string $errorMessage): void
    {
        echo json_encode([
            'status' => 'error',
            'error' => $errorMessage,
        ]);
        wp_die();
    }

    private static function isPageSafeToLoadScripts(): bool
    {
        global $pagenow;
        return $pagenow === self::NEW_USER_PAGE_IDENTIFIER;
    }
}
