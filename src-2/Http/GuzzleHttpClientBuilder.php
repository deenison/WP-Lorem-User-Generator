<?php

namespace LoremUserGenerator\Http;

use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;

final class GuzzleHttpClientBuilder implements HttpClientBuilderInterface
{
    private $guzzleClient;

    public function __construct()
    {
        $this->guzzleClient = new Client([
            'http_errors' => true,
        ]);
    }

    public function build(): ClientInterface
    {
        return new GuzzleHttpClientAdapter($this->guzzleClient);
    }
}
