<?php

namespace Test\LoremUserGenerator\DataProvider\Provider\RandomUserMe;

use LoremUserGenerator\DataProvider\Provider\RandomUserMe\RandomUserMeHttpRequestBuilder;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class RandomUserMeHttpRequestBuilderTest extends TestCase
{
    public function test_Build_ShouldReturnExpectedHttpRequest()
    {
        $requestBuilder = new RandomUserMeHttpRequestBuilder();
        $httpRequest = $requestBuilder->build();

        Assert::assertEquals('GET', $httpRequest->getMethod());
        Assert::assertEquals('https://randomuser.me/api/?inc=name,login,email', (string)$httpRequest->getUri());
        Assert::assertEmpty((string)$httpRequest->getBody());
    }
}
