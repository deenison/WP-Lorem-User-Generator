<?php

namespace Test\LoremUserGenerator\DataProvider\Provider\RandomUserMe;

use LoremUserGenerator\DataProvider\Exception\DataProviderException;
use LoremUserGenerator\DataProvider\Provider\RandomUserMe\RandomUserMeGateway;
use PHPUnit\Framework\Assert;
use Test\LoremUserGenerator\Http\Client\Fake\FakeHttpClient;
use Test\LoremUserGenerator\Http\Response\FakeHttpResponse;
use Test\LoremUserGenerator\TestCase;

class RandomUserMeGatewayTest extends TestCase
{
    public function test_FetchRandomUser_ShouldReturnExpectedUser(): void
    {
        $httpClient = new FakeHttpClient();
        $successfulHttpResponseStubBody = RandomUserMeHttpResponseBodyStubFactory::getSuccessfulResponseBodyStub(
            $firstName = 'Jack',
            $lastName = 'Bauer',
            $email = 'jackbauer@local.sbx',
            $username = 'jackbauer',
            $password = 'Chloe, schematics!'
        );
        $successfulHttpResponseStub = new FakeHttpResponse($httpStatusCode = 200, $successfulHttpResponseStubBody);
        $httpClient->stubHttpResponse($successfulHttpResponseStub);

        $gateway = new RandomUserMeGateway($httpClient);

        [$user] = $gateway->fetchRandomUser();

        Assert::assertEquals('Jack', $user->getFirstName());
        Assert::assertEquals('Bauer', $user->getLastName());
        Assert::assertEquals('jackbauer@local.sbx', $user->getEmail());
        Assert::assertEquals('jackbauer', $user->getUsername());
        Assert::assertEquals('Chloe, schematics!', $user->getPassword());
    }

    public function test_FetchRandomUser_ShouldThrowException_IfApiReturnedAnError(): void
    {
        $httpClient = new FakeHttpClient();
        $failedHttpResponseStubBody = RandomUserMeHttpResponseBodyStubFactory::getFailedResponseBodyStub($errorMessage = 'Ops, something has gone wrong');
        $failedHttpResponseStub = new FakeHttpResponse($httpStatusCode = 400, $failedHttpResponseStubBody);
        $httpClient->stubHttpResponse($failedHttpResponseStub);

        $gateway = new RandomUserMeGateway($httpClient);

        $this->expectException(DataProviderException::class);
        $this->expectExceptionMessage('Ops, something has gone wrong');

        $gateway->fetchRandomUser();
    }
}
