<?php

namespace LoremUserGenerator\Core;

use LoremUserGenerator\Core\User\UserEntity;
use LoremUserGenerator\DataProvider\Provider\RandomUserMe\RandomUserMeGateway;
use Psr\Http\Client\ClientInterface;

final class LoremUserGeneratorFacade
{
    /** @var ClientInterface */
    private $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function fetchUserWithRandomData(): UserEntity
    {
        $gateway = new RandomUserMeGateway($this->httpClient);
        $gateway->fetchRandomUser();
    }
}
