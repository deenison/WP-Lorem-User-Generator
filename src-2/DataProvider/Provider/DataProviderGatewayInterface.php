<?php

namespace LoremUserGenerator\DataProvider\Provider;

use LoremUserGenerator\User\UserEntity;

interface DataProviderGatewayInterface
{
    public function fetchRandomUser(array $filters = []): array;
}
