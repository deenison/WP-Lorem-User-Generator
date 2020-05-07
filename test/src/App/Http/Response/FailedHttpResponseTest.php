<?php

namespace Test\LoremUserGenerator\App\Http\Response;

use LoremUserGenerator\App\Http\Response\FailedHttpResponse;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class FailedHttpResponseTest extends TestCase
{
    public function test_JsonEncoding_ShouldResultInExpectedJsonString(): void
    {
        $failedHttpResponse = new FailedHttpResponse($failMessage = 'You cannot do this');

        $failedHttpResponseAsJson = json_encode($failedHttpResponse);

        $expectedFailedHttpResponseAsJson = <<<JSON
{
    "status": "fail",
    "error": "You cannot do this"
}
JSON;

        Assert::assertJsonStringEqualsJsonString($expectedFailedHttpResponseAsJson, $failedHttpResponseAsJson);
    }

    public function test_Instance_ShouldThrowException_IfFailMessageIsEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('A fail message must be provided');

        new FailedHttpResponse($failMessage = '');
    }
}
