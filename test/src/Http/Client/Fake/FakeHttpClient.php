<?php

namespace Test\LoremUserGenerator\Http\Client\Fake;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class FakeHttpClient implements ClientInterface
{
    /** @var ResponseInterface */
    private $stubbedHttpResponse;
    /** @var ClientExceptionInterface */
    private $stubbedException;

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        if (!empty($this->stubbedException)) {
            throw new $this->stubbedException;
        }

        if (is_null($this->stubbedHttpResponse)) {
            throw new \LogicException('There is no HTTP Response stubbed');
        }

        return $this->stubbedHttpResponse;
    }

    public function stubException(ClientExceptionInterface $exception): void
    {
        $this->stubbedException = $exception;
    }

    public function stubHttpResponse(ResponseInterface $httpResponse): void
    {
        $this->stubbedHttpResponse = $httpResponse;
    }
}
