<?php

namespace LoremUserGenerator\DataProvider\Provider\RandomUserMe;

use LoremUserGenerator\Core\User\UserEntity;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

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

        return RandomUserMeHttpResponseParser::parseHttpResponse($httpResponse);
    }

    private static function buildHttpRequest(): RequestInterface
    {
        return (new RandomUserMeHttpRequestBuilder())->build();
    }
}
