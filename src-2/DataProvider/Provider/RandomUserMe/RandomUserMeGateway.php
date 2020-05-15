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

    public function fetchRandomUser(array $options = []): array
    {
        $options = self::buildOptionsFromArray($options);

        $httpRequest = self::buildHttpRequest($options);
        $httpResponse = $this->httpClient->sendRequest($httpRequest);

        return RandomUserMeHttpResponseParser::parseHttpResponse($httpResponse);
    }

    private static function buildOptionsFromArray(array $filtersAsArray): RandomUserMeFilters
    {
        $filtersEntityBuilder = RandomUserMeFilters::builder();

        $results = $filtersAsArray['results'] ?? 0;
        if (is_numeric($results) && $results > 1) {
            $filtersEntityBuilder->withResults($results);
        }

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
