<?php

namespace LoremUserGenerator\Http\Client;

use Psr\Http\Client\ClientInterface;

interface HttpClientBuilderInterface
{
    public function build(): ClientInterface;
}
