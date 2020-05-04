<?php

namespace Test\LoremUserGenerator\Http\Adapter\Guzzle;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use LoremUserGenerator\Http\Adapter\Guzzle\GuzzleHttpClientAdapter;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class GuzzleHttpClientAdapterTest extends TestCase
{
    public function test_SendRequest_ShouldResultInExpectedHttpResponse(): void
    {
        $guzzleClient = self::buildGuzzleClientWithMockedResponse(
            $responseHttpStatusCode = 200,
            $responseHeaders = ['X-Foo' => 'lorem'],
            $responseBody = 'It works'
        );
        $guzzleHttpClientAdapter = new GuzzleHttpClientAdapter($guzzleClient);

        $httpRequest = new Request($httpMethod = 'GET', $uri = '/');
        $httpResponse = $guzzleHttpClientAdapter->sendRequest($httpRequest);

        Assert::assertEquals(200, $httpResponse->getStatusCode());
        Assert::assertEquals('lorem', $httpResponse->getHeaderLine('X-Foo'));
        Assert::assertEquals('It works', (string)$httpResponse->getBody());
    }

    private static function buildGuzzleClientWithMockedResponse(
        int $responseHttpStatusCode,
        array $responseHeaders,
        string $responseBody
    ): Client {
        $responseMockHandler = new MockHandler([
            new Response($responseHttpStatusCode, $responseHeaders, $responseBody),
        ]);
        $responseHandlerStack = HandlerStack::create($responseMockHandler);

        return new Client([
            'handler' => $responseHandlerStack,
        ]);
    }
}
