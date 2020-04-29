<?php

namespace LoremUserGenerator\Http;

use GuzzleHttp\Client;
use LoremUserGenerator\Core\Http\HttpClientBuilderInterface;
use Psr\Http\Client\ClientInterface;

final class GuzzleHttpClientBuilder implements HttpClientBuilderInterface
{
    private $guzzleClient;

    public function __construct()
    {
        $this->guzzleClient = new Client();
    }

    public function build(): ClientInterface
    {
        return new GuzzleHttpClientAdapter($this->guzzleClient);
    }
}