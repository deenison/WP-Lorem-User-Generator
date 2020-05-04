<?php

namespace Test\LoremUserGenerator\Http;

use LoremUserGenerator\Http\HttpClientBuilderInterface;
use Psr\Http\Client\ClientInterface;

final class FakeHttpClientBuilder implements HttpClientBuilderInterface
{
    public function build(): ClientInterface
    {
        return new FakeHttpClient();
    }
}
