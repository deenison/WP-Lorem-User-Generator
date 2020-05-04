<?php

namespace LoremUserGenerator\Http\Request;

use Psr\Http\Message\RequestInterface;

interface HttpRequestBuilderInterface
{
    public function build(): RequestInterface;
}
