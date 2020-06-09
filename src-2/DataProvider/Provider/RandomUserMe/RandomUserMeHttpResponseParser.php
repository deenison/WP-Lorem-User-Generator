<?php

namespace LoremUserGenerator\DataProvider\Provider\RandomUserMe;

if (!defined('ABSPATH')) exit;

use LoremUserGenerator\DataProvider\Exception\DataProviderException;
use LoremUserGenerator\User\UserEntity;
use Psr\Http\Message\ResponseInterface;

final class RandomUserMeHttpResponseParser
{
    private function __construct()
    {
    }

    public static function parseHttpResponse(ResponseInterface $httpResponse): array
    {
        $response = json_decode($httpResponse->getBody()->getContents(), true);
        if (empty($response)) {
            throw new DataProviderException('No response received');
        }

        $errorMessage = $response['error'] ?? '';
        if (!empty($errorMessage)) {
            throw new DataProviderException($errorMessage);
        }

        $results = $response['results'] ?? [];
        return self::parseUsersFromResponseResults($results);
    }

    private static function parseUsersFromResponseResults(array $responseResults): array
    {
        $usersRawData = [];

        foreach ($responseResults as $randomUserRawData) {
            $usersRawData[] = self::buildUserFromDecodedResponse($randomUserRawData);
        }

        return $usersRawData;
    }

    private static function buildUserFromDecodedResponse(array $decodedResponse): UserEntity
    {
        $firstName = self::extractFirstNameOrCry($decodedResponse);
        $lastName = self::extractLastNameOrCry($decodedResponse);
        $email = self::extractEmailOrCry($decodedResponse);
        $username = self::extractUsernameOrCry($decodedResponse);
        $password = self::extractPasswordOrCry($decodedResponse);

        return UserEntity::builder()
            ->withFirstName($firstName)
            ->withLastName($lastName)
            ->withEmail($email)
            ->withUsername($username)
            ->withPassword($password)
            ->build();
    }

    private static function extractFirstNameOrCry(array $decodedResponse): string
    {
        $firstName = $decodedResponse['name']['first'] ?? '';
        self::isNotEmptyOrCry($firstName, 'Invalid `first_name`');
        return $firstName;
    }

    private static function extractLastNameOrCry(array $decodedResponse): string
    {
        $lastName = $decodedResponse['name']['last'] ?? '';
        self::isNotEmptyOrCry($lastName, 'Invalid `last_name`');
        return $lastName;
    }

    private static function extractEmailOrCry(array $decodedResponse): string
    {
        $email = $decodedResponse['email'] ?? '';
        self::isNotEmptyOrCry($email, 'Invalid `email`');
        return $email;
    }

    private static function extractUsernameOrCry(array $decodedResponse): string
    {
        $username = $decodedResponse['login']['username'] ?? '';
        self::isNotEmptyOrCry($username, 'Invalid `username`');
        return $username;
    }

    private static function extractPasswordOrCry(array $decodedResponse): string
    {
        $password = $decodedResponse['login']['password'] ?? '';
        self::isNotEmptyOrCry($password, 'Invalid `password`');
        return $password;
    }

    private static function isNotEmptyOrCry($subject, string $exceptionMessage): void
    {
        if (empty($subject)) {
            throw new DataProviderException($exceptionMessage);
        }
    }
}
