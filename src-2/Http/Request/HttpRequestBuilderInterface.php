<?php

namespace LoremUserGenerator\Http\Request;

if (!defined('ABSPATH')) exit;

use Psr\Http\Message\RequestInterface;

interface HttpRequestBuilderInterface
{
    public function build(): RequestInterface;
}
