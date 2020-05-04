<?php

namespace Test\LoremUserGenerator\Http\Response;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class FakeHttpResponse implements ResponseInterface
{
    /** @var ResponseInterface */
    private $httpResponse;

    public function __construct(int $httpStatusCode, string $responseBody)
    {
        $this->httpResponse = new Response($httpStatusCode, [], $responseBody);
    }

    public function getProtocolVersion()
    {
        return $this->httpResponse->getProtocolVersion();
    }

    public function withProtocolVersion($version)
    {
        return $this->httpResponse->withProtocolVersion($version);
    }

    public function getHeaders()
    {
        return $this->httpResponse->getHeaders();
    }

    public function hasHeader($name)
    {
        return $this->httpResponse->hasHeader($name);
    }

    public function getHeader($name)
    {
        return $this->httpResponse->getHeader($name);
    }

    public function getHeaderLine($name)
    {
        return $this->httpResponse->getHeaderLine($name);
    }

    public function withHeader($name, $value)
    {
        return $this->httpResponse->withHeader($name, $value);
    }

    public function withAddedHeader($name, $value)
    {
        return $this->httpResponse->withAddedHeader($name, $value);
    }

    public function withoutHeader($name)
    {
        return $this->httpResponse->withoutHeader($name);
    }

    public function getBody()
    {
        return $this->httpResponse->getBody();
    }

    public function withBody(StreamInterface $body)
    {
        return $this->httpResponse->withBody($body);
    }

    public function getStatusCode()
    {
        return $this->httpResponse->getStatusCode();
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        return $this->httpResponse->withStatus($code, $reasonPhrase);
    }

    public function getReasonPhrase()
    {
        return $this->httpResponse->getReasonPhrase();
    }
}
