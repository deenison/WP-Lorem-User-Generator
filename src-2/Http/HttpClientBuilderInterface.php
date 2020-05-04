<?php

namespace LoremUserGenerator\Http;

use Psr\Http\Client\ClientInterface;

interface HttpClientBuilderInterface
{
    public function build(): ClientInterface;
}
