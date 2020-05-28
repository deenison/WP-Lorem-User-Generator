<?php

namespace LoremUserGenerator\App\Modules\AddMultipleUsers\RequestData;

final class AddMultipleUsersRequestDataRetrieverService
{
    private const QUANTITY_REQUEST_KEY = 'qty';

    private function __construct()
    {
    }

    public static function retrieveQuantity(): int
    {
        $unsanitizedQuantity = $_GET[self::QUANTITY_REQUEST_KEY] ?? '';
        $sanitizedQuantity = AddMultipleUsersRequestDataSanitizerService::sanitizeQuantity($unsanitizedQuantity);
        AddMultipleUsersRequestDataValidatorService::isQuantityValidOrCry($sanitizedQuantity);
        return $sanitizedQuantity;
    }

    public static function retrieveUsersFromPost(): array
    {
        $usersFromRequest = $_POST['users'] ?? [];
        if (!is_array($usersFromRequest) || empty($usersFromRequest)) {
            return [];
        }

        return array_map(
            function ($userFromRequest) {
                $firstName = $userFromRequest['first_name'] ?? '';
                $lastName = $userFromRequest['last_name'] ?? '';
                $email = $userFromRequest['email'] ?? '';
                $username = $userFromRequest['username'] ?? '';
                $password = $userFromRequest['password'] ?? '';

                return [
                    'first_name' => filter_var(trim($firstName), FILTER_SANITIZE_STRING),
                    'last_name' => filter_var(trim($lastName), FILTER_SANITIZE_STRING),
                    'email' => filter_var(trim($email), FILTER_SANITIZE_STRING),
                    'username' => filter_var(trim($username), FILTER_SANITIZE_STRING),
                    'password' => filter_var(trim($password), FILTER_SANITIZE_STRING),
                ];
            },
            $usersFromRequest
        );
    }
}