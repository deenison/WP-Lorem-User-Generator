<?php

namespace LoremUserGenerator\DataProvider\Provider\RandomUserMe;

use LoremUserGenerator\Core\User\UserEntity;
use LoremUserGenerator\DataProvider\Exception\DataProviderException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class RandomUserMeGateway
{
    /** @var ClientInterface */
    private $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function fetchRandomUser(): UserEntity
    {
        $httpRequest = self::buildHttpRequest();
        $httpResponse = $this->httpClient->sendRequest($httpRequest);

        return self::parseHttpResponse($httpResponse);
    }

    private static function buildHttpRequest(): RequestInterface
    {
        return (new RandomUserMeHttpRequestBuilder())->build();
    }

    private static function parseHttpResponse(ResponseInterface $httpResponse): UserEntity
    {
        $response = json_decode($httpResponse->getBody()->getContents());

        $errorMessage = $response->error ?? '';
        if (!empty($errorMessage)) {
            throw new DataProviderException($errorMessage);
        }

        $response = $response->results[0];

        return UserEntity::builder()
            ->withFirstName($response->name->first ?? '')
            ->withLastName($response->name->last ?? '')
            ->withEmail($response->email ?? '')
            ->withUsername($response->login->username ?? '')
            ->withPassword($response->login->password ?? '')
            ->build();
    }
}
