<?php

namespace Test\LoremUserGenerator\Http;

use LoremUserGenerator\Http\Adapter\Guzzle\GuzzleHttpClientAdapter;
use LoremUserGenerator\Http\HttpClientService;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class HttpClientServiceTest extends TestCase
{
    public function test_GetHttpClient_ShouldReturnDefaultHttpClient_IfHttpClientBuilderIsNull(): void
    {
        $httpClient = (new HttpClientService())->getHttpClient();
        Assert::assertInstanceOf(GuzzleHttpClientAdapter::class, $httpClient);
    }

    public function test_GetHttpClient_ShouldReturnGivenHttpClient_IfHttpClientBuilderIsNotNull(): void
    {
        $fakeHttpClientBuilder = new FakeHttpClientBuilder();

        $httpClient = (new HttpClientService($fakeHttpClientBuilder))->getHttpClient();
        Assert::assertInstanceOf(FakeHttpClient::class, $httpClient);
    }
}
