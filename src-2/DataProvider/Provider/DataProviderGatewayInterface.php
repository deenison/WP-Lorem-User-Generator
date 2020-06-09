<?php

namespace LoremUserGenerator\DataProvider\Provider;

if (!defined('ABSPATH')) exit;

interface DataProviderGatewayInterface
{
    public function fetchRandomUser(array $filters = []): array;
}
