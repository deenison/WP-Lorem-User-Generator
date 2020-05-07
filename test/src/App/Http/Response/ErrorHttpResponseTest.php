<?php

namespace Test\LoremUserGenerator\App\Http\Response;

use LoremUserGenerator\App\Http\Response\ErrorHttpResponse;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ErrorHttpResponseTest extends TestCase
{
    public function test_JsonEncoding_ShouldResultInExpectedJsonString(): void
    {
        $errorHttpResponse = new ErrorHttpResponse($errorMessage = 'Something went south');

        $errorHttpResponseAsJson = json_encode($errorHttpResponse);

        $expectedFailedHttpResponseAsJson = <<<JSON
{
    "status": "error",
    "error": "Something went south"
}
JSON;

        Assert::assertJsonStringEqualsJsonString($expectedFailedHttpResponseAsJson, $errorHttpResponseAsJson);
    }

    public function test_Instance_ShouldThrowException_IfFailMessageIsEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('An error message must be provided');

        new ErrorHttpResponse($errorMessage = '');
    }
}
