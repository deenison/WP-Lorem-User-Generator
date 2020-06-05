<?php

namespace LoremUserGenerator\DataProvider\Provider\RandomUserMe;

use GuzzleHttp\Psr7\Request;
use LoremUserGenerator\DataProvider\Provider\RandomUserMe\Filter\RandomUserMeFilters;
use LoremUserGenerator\Http\Request\HttpRequestBuilderInterface;
use Psr\Http\Message\RequestInterface;

final class RandomUserMeHttpRequestBuilder implements HttpRequestBuilderInterface
{
    private const HTTP_METHOD_GET = 'GET';
    private const API_URI = 'https://randomuser.me/api';

    /** @var RandomUserMeFilters */
    private $filters;

    public function __construct(?RandomUserMeFilters $filters = null)
    {
        $this->filters = $filters ?? RandomUserMeFilters::builder()->build();
    }

    public function build(): RequestInterface
    {
        $uri = $this->buildUri();
        return new Request(self::HTTP_METHOD_GET, $uri);
    }

    private function buildUri(): string
    {
        return self::API_URI .'?'. self::buildUriParams($this->filters);
    }

    private static function buildUriParams(RandomUserMeFilters $filters): string
    {
        $filtersAsArray = [
            'inc' => 'name,login,email',
        ];

        $results = $filters->getResults();
        if (!empty($results)) {
            $filtersAsArray['results'] = $results;
        }

        $gender = $filters->getGender();
        if (!empty($gender)) {
            $filtersAsArray['gender'] = $gender;
        }

        $nationalities = $filters->getNationalities();
        if (!empty($nationalities)) {
            $filtersAsArray['nat'] = strtolower(implode(',', $nationalities));
        }

        return http_build_query($filtersAsArray);
    }
}
