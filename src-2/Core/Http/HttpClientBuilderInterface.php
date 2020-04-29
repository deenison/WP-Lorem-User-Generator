<?php

namespace LoremUserGenerator\Core\Http;

use Psr\Http\Client\ClientInterface;

interface HttpClientBuilderInterface
{
    public function build(): ClientInterface;
}
