<?php

namespace Test\LoremUserGenerator\App\Http\Response;

use LoremUserGenerator\App\Http\Response\SuccessfulHttpResponse;
use LoremUserGenerator\User\UserEntity;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class SuccessfulHttpResponseTest extends TestCase
{
    public function test_JsonEncodingInstance_ShouldResultInExpectedJsonString(): void {
        $user = UserEntity::builder()
            ->withFirstName('Jack')
            ->withLastName('Sheppard')
            ->withEmail('jacksheppard@sbx.local')
            ->withUsername('jack')
            ->withPassword('we have to go back')
            ->build();

        $successfulHttpResponse = new SuccessfulHttpResponse([$user]);
        $successfulHttpResponseAsJson = json_encode($successfulHttpResponse);

        $expectedSuccessfulHttpResponseAsJson = <<<JSON
{
    "status": "success",
    "data": [{
        "first_name": "Jack",
        "last_name": "Sheppard",
        "email": "jacksheppard@sbx.local",
        "username": "jack",
        "password": "we have to go back"
    }]
}
JSON;

        Assert::assertJsonStringEqualsJsonString($expectedSuccessfulHttpResponseAsJson, $successfulHttpResponseAsJson);
    }
}
