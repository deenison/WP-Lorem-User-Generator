<?php

namespace LoremUserGenerator\Http\Client\Guzzle;

if (!defined('ABSPATH')) exit;

use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class GuzzleHttpClientAdapter implements ClientInterface
{
    /** @var Client */
    private $guzzleClient;

    public function __construct(Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->guzzleClient->send($request);
    }
}
