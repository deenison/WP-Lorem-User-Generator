<?php

namespace LoremUserGenerator\Core\Http;

use Psr\Http\Message\RequestInterface;

interface HttpRequestBuilderInterface
{
    public function build(): RequestInterface;
}
