<?php

namespace Test\LoremUserGenerator\Http\Client\Fake;

use LoremUserGenerator\Http\Client\HttpClientBuilderInterface;
use Psr\Http\Client\ClientInterface;

final class FakeHttpClientBuilder implements HttpClientBuilderInterface
{
    public function build(): ClientInterface
    {
        return new FakeHttpClient();
    }
}
