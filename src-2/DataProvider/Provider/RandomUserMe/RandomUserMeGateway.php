<?php

namespace LoremUserGenerator\DataProvider\Provider\RandomUserMe;

use LoremUserGenerator\DataProvider\Provider\DataProviderGatewayInterface;
use LoremUserGenerator\DataProvider\Provider\RandomUserMe\Filter\RandomUserMeFilters;
use LoremUserGenerator\DataProvider\Provider\RandomUserMe\Filter\RandomUserMeFiltersBuilder;
use LoremUserGenerator\User\UserEntity;
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

    public function fetchRandomUser(array $filters = []): UserEntity
    {
        $filters = self::buildFiltersFromArray($filters);

        $httpRequest = self::buildHttpRequest($filters);
        $httpResponse = $this->httpClient->sendRequest($httpRequest);

        return RandomUserMeHttpResponseParser::parseHttpResponse($httpResponse);
    }

    private static function buildFiltersFromArray(array $filtersAsArray): RandomUserMeFilters
    {
        $filtersEntityBuilder = RandomUserMeFilters::builder();

        $gender = $filtersAsArray['gender'] ?? '';
        if (!empty($gender)) {
            $filtersEntityBuilder->withGender($gender);
        }

        return $filtersEntityBuilder->build();
    }

    private static function buildHttpRequest(RandomUserMeFilters $filters): RequestInterface
    {
        return (new RandomUserMeHttpRequestBuilder($filters))->build();
    }
}
