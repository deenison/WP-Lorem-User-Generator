<?php

namespace LoremUserGenerator;

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

    public function fetchUserWithRandomData(array $filters): array
    {
        $gateway = new RandomUserMeGateway($this->httpClient);

        return $gateway->fetchRandomUser($filters);
    }
}
