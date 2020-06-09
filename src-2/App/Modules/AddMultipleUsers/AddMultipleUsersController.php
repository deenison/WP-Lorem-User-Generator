<?php

namespace LoremUserGenerator\App\Modules\AddMultipleUsers;

use LoremUserGenerator\App\Asset\AssetEnqueuer;
use LoremUserGenerator\App\DataProvider\AppDataProviderService;
use LoremUserGenerator\App\Http\Response\HttpResponseDispatcher;
use LoremUserGenerator\App\Modules\AddMultipleUsers\Http\Request\FetchUsers\FetchUsersRequestPayload;
use LoremUserGenerator\App\Modules\AddMultipleUsers\Http\Response\SuccessfulSaveHttpResponse;
use LoremUserGenerator\App\Modules\AddMultipleUsers\RequestData\AddMultipleUsersRequestDataRetrieverService;
use LoremUserGenerator\App\Nonce\NewUsersNonceService;
use LoremUserGenerator\App\Persistence\WordpressPersistenceService;
use LoremUserGenerator\App\Template\TemplateRenderer;
use LoremUserGenerator\App\User\WordpressUser;
use LoremUserGenerator\App\User\WordpressUserRepository;
use LoremUserGenerator\App\UserRole\UserRoleService;
use LoremUserGenerator\Persistence\PersistenceServiceException;
use LoremUserGenerator\User\UserEntity;

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
            add_action('admin_enqueue_scripts', [self::class, 'registerStyleScripts']);
        }

        add_action('wp_ajax_lorem_user_generator_fetch_multiple_random_data', [self::class, 'fetchRandomData']);
        add_action('wp_ajax_lorem_user_generator_save_multiple_random_data', [self::class, 'saveRandomData']);

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
        $context = [
            'defaultUserRole' => get_option('default_role'),
            'usersRoles' => UserRoleService::retrieveAllAvailableUserRoles(),
        ];

        TemplateRenderer::render('add-multiple-new', $context);
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

    public static function registerStyleScripts(): void
    {
        AssetEnqueuer::enqueueStylesheetFile(self::NEW_USER_PAGE_IDENTIFIER, 'add-multiple-users');
    }

    public static function fetchRandomData(): void
    {
        if (!NewUsersNonceService::isNonceInRequestValid()) {
            HttpResponseDispatcher::dispatchFailedResponse('Please, refresh your page and try again.');
        }

        try {
            $requestPayload = FetchUsersRequestPayload::fromRequest();
        } catch (\InvalidArgumentException $exception) {
            HttpResponseDispatcher::dispatchFailedResponse($exception->getMessage());
        } catch (\Throwable $exception) {
            HttpResponseDispatcher::dispatchErrorResponse($exception->getMessage());
        }

        $httpResponse = (new AppDataProviderService())->fetchRandomUser($requestPayload->toArray());
        HttpResponseDispatcher::dispatch($httpResponse);
    }

    public static function saveRandomData(): void
    {
        $persistenceService = new WordpressPersistenceService();
        $repository = new WordpressUserRepository($persistenceService);

        $userRole = AddMultipleUsersRequestDataRetrieverService::retrieveRoleFromPost();

        $users = self::retrieveUsersFromPost($userRole);
        try {
            foreach ($users as $user) {
                $repository->store($user);
            }
        } catch (PersistenceServiceException $exception) {
            HttpResponseDispatcher::dispatchFailedResponse($exception->getMessage());
        }

        $httpResponse = new SuccessfulSaveHttpResponse();
        HttpResponseDispatcher::dispatch($httpResponse);
    }

    private static function retrieveUsersFromPost(string $userRole): array
    {
        $usersFromRequest = AddMultipleUsersRequestDataRetrieverService::retrieveUsersFromPost();
        return array_map(
            function ($userFromRequest) use ($userRole) {
                $user = self::buildUserEntityFromRequestArray($userFromRequest);
                return WordpressUser::fromUser($user, $userRole);
            },
            $usersFromRequest
        );
    }

    private static function buildUserEntityFromRequestArray(array $userFromRequest): UserEntity
    {
        return UserEntity::builder()
            ->withFirstName($userFromRequest['first_name'])
            ->withLastName($userFromRequest['last_name'])
            ->withEmail($userFromRequest['email'])
            ->withUsername($userFromRequest['username'])
            ->withPassword($userFromRequest['password'])
            ->build();
    }
}
