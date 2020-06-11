<?php

namespace LoremUserGenerator\Http\Client;

if (!defined('ABSPATH')) exit;

use Psr\Http\Client\ClientInterface;

interface HttpClientBuilderInterface
{
    public function build(): ClientInterface;
}
