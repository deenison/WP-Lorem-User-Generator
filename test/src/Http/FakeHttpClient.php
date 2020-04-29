<?php

namespace Test\LoremUserGenerator\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class FakeHttpClient implements ClientInterface
{
    /** @var ResponseInterface */
    private $stubbedHttpResponse;

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        if (is_null($this->stubbedHttpResponse)) {
            throw new \LogicException('There is no HTTP Response stubbed');
        }

        return $this->stubbedHttpResponse;
    }

    public function stubHttpResponse(ResponseInterface $httpResponse): void
    {
        $this->stubbedHttpResponse = $httpResponse;
    }
}
