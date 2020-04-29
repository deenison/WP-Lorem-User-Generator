<?php

namespace Test\LoremUserGenerator\DataProvider\Provider\RandomUserMe;

use LoremUserGenerator\DataProvider\Exception\DataProviderException;
use LoremUserGenerator\DataProvider\Provider\RandomUserMe\RandomUserMeGateway;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Test\LoremUserGenerator\Http\FakeHttpClient;
use Test\LoremUserGenerator\Http\FakeHttpResponse;

class RandomUserMeGatewayTest extends TestCase
{
    public function test_FetchRandomUser_ShouldReturnExpectedUser(): void
    {
        $httpClient = new FakeHttpClient();
        $successfulHttpResponseStubBody = self::getSuccessfulResponseBody(
            $firstName = 'Jack',
            $lastName = 'Bauer',
            $email = 'jackbauer@local.sbx',
            $username = 'jackbauer',
            $password = 'Chloe, schematics!'
        );
        $successfulHttpResponseStub = new FakeHttpResponse($httpStatusCode = 200, $successfulHttpResponseStubBody);
        $httpClient->stubHttpResponse($successfulHttpResponseStub);

        $gateway = new RandomUserMeGateway($httpClient);

        $user = $gateway->fetchRandomUser();

        Assert::assertEquals('Jack', $user->getFirstName());
        Assert::assertEquals('Bauer', $user->getLastName());
        Assert::assertEquals('jackbauer@local.sbx', $user->getEmail());
        Assert::assertEquals('jackbauer', $user->getUsername());
        Assert::assertEquals('Chloe, schematics!', $user->getPassword());
    }

    public function test_FetchRandomUser_ShouldThrowException_IfApiReturnedAnError(): void
    {
        $httpClient = new FakeHttpClient();
        $failedHttpResponseStubBody = self::getFailedResponseBody($errorMessage = 'Ops, something has gone wrong');
        $failedHttpResponseStub = new FakeHttpResponse($httpStatusCode = 400, $failedHttpResponseStubBody);
        $httpClient->stubHttpResponse($failedHttpResponseStub);

        $gateway = new RandomUserMeGateway($httpClient);

        $this->expectException(DataProviderException::class);
        $this->expectExceptionMessage('Ops, something has gone wrong');

        $gateway->fetchRandomUser();
    }

    private static function getSuccessfulResponseBody(
        string $firstName,
        string $lastName,
        string $email,
        string $username,
        string $password
    ): string {
        return <<<JSON
{
  "results":[
    {
      "name":{
        "title":"Mr",
        "first":"{$firstName}",
        "last":"{$lastName}"
      },
      "email":"{$email}",
      "login":{
        "uuid":"a2fd2a68-abdc-4109-b367-b7172e26f85a",
        "username":"{$username}",
        "password":"{$password}",
        "salt":"QRZWecMl",
        "md5":"3636875a5b9ac790f22382515719d88e",
        "sha1":"4edf0aabb34413389e727a3d526c16846c674ddb",
        "sha256":"25dbcdfee1f2d4993efd723acbe48e3d77b8c61f275fe77945802b9535ef663a"
      }
    }
  ],
  "info":{
    "seed":"2560177d92b6c4e4",
    "results":1,
    "page":1,
    "version":"1.3"
  }
}
JSON;
    }

    private static function getFailedResponseBody(string $errorMessage): string
    {
        return <<<JSON
{
  "error": "{$errorMessage}"
}
JSON;

    }
}
