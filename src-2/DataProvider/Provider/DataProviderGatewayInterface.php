<?php

namespace LoremUserGenerator\DataProvider\Provider;

use LoremUserGenerator\Core\User\UserEntity;

interface DataProviderGatewayInterface
{
    public function fetchRandomUser(): UserEntity;
}
