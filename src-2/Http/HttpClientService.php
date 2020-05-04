<?php

namespace LoremUserGenerator\Http;

use LoremUserGenerator\Http\Client\Guzzle\GuzzleHttpClientBuilder;
use LoremUserGenerator\Http\Client\HttpClientBuilderInterface;
use Psr\Http\Client\ClientInterface;

final class HttpClientService
{
    /** @var HttpClientBuilderInterface */
    private $httpClientBuilder;

    public function __construct(?HttpClientBuilderInterface $httpClientBuilder = null)
    {
        $this->httpClientBuilder = $httpClientBuilder ?? self::getDefaultHttpClientBuilder();
    }

    public function getHttpClient(): ClientInterface
    {
        return $this->httpClientBuilder->build();
    }

    private static function getDefaultHttpClientBuilder(): HttpClientBuilderInterface
    {
        return new GuzzleHttpClientBuilder();
    }
}
