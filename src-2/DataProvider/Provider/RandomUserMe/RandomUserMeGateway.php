<?php

namespace LoremUserGenerator\DataProvider\Provider\RandomUserMe;

if (!defined('ABSPATH')) exit;

use LoremUserGenerator\DataProvider\Provider\DataProviderGatewayInterface;
use LoremUserGenerator\DataProvider\Provider\RandomUserMe\Filter\RandomUserMeFilters;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

final class RandomUserMeGateway implements DataProviderGatewayInterface
{
    /** @var ClientInterface */
    private $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function fetchRandomUser(array $options = []): array
    {
        $filters = RandomUserMeFilters::fromArray($options);

        $httpRequest = self::buildHttpRequest($filters);
        $httpResponse = $this->httpClient->sendRequest($httpRequest);

        return RandomUserMeHttpResponseParser::parseHttpResponse($httpResponse);
    }

    private static function buildHttpRequest(RandomUserMeFilters $filters): RequestInterface
    {
        return (new RandomUserMeHttpRequestBuilder($filters))->build();
    }
}
