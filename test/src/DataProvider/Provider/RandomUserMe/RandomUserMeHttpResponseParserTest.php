<?php

namespace Test\LoremUserGenerator\DataProvider\Provider\RandomUserMe;

use LoremUserGenerator\DataProvider\Exception\DataProviderException;
use LoremUserGenerator\DataProvider\Provider\RandomUserMe\RandomUserMeHttpResponseParser;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Test\LoremUserGenerator\Http\Response\FakeHttpResponse;

class RandomUserMeHttpResponseParserTest extends TestCase
{
    public function test_ParseHttpResponse_ShouldReturnExpectedUser(): void
    {
        $httpResponseBody = RandomUserMeHttpResponseBodyStubFactory::getSuccessfulResponseBodyStub(
            $firstName = 'Jack',
            $lastName = 'Bauer',
            $email = 'jackbauer@local.sbx',
            $username = 'jackbauer',
            $password = 'Chloe, schematics!'
        );
        $httpResponse = new FakeHttpResponse($httpStatusCode = 200, $httpResponseBody);

        [$user] = RandomUserMeHttpResponseParser::parseHttpResponse($httpResponse);

        Assert::assertEquals('Jack', $user->getFirstName());
        Assert::assertEquals('Bauer', $user->getLastName());
        Assert::assertEquals('jackbauer@local.sbx', $user->getEmail());
        Assert::assertEquals('jackbauer', $user->getUsername());
        Assert::assertEquals('Chloe, schematics!', $user->getPassword());
    }

    public function test_ParseHttpResponse_ShouldThrowException_IfApiRespondWithError(): void
    {
        $errorMessage = 'Something went bad: '. uniqid();
        $httpResponseBody = RandomUserMeHttpResponseBodyStubFactory::getFailedResponseBodyStub($errorMessage);
        $httpResponse = new FakeHttpResponse($httpStatusCode = mt_rand(200, 500), $httpResponseBody);

        $this->expectException(DataProviderException::class);
        $this->expectExceptionMessage($errorMessage);

        RandomUserMeHttpResponseParser::parseHttpResponse($httpResponse);
    }

    public static function provideUnexpectedResponses(): iterable
    {
        $firstNameMissing = RandomUserMeHttpResponseBodyStubFactory::getSuccessfulResponseBodyStub($firstName = '', $lastName = 'LastName', $email = 'a@b.c', $username = 'theusername', $password = 'secret');
        $lastNameMissing = RandomUserMeHttpResponseBodyStubFactory::getSuccessfulResponseBodyStub($firstName = 'FirstName', $lastName = '', $email = 'a@b.c', $username = 'theusername', $password = 'secret');
        $emailMissing = RandomUserMeHttpResponseBodyStubFactory::getSuccessfulResponseBodyStub($firstName = 'FirstName', $lastName = 'LastName', $email = '', $username = 'theusername', $password = 'secret');
        $usernameMissing = RandomUserMeHttpResponseBodyStubFactory::getSuccessfulResponseBodyStub($firstName = 'FirstName', $lastName = 'LastName', $email = 'a@b.c', $username = '', $password = 'secret');
        $passwordMissing = RandomUserMeHttpResponseBodyStubFactory::getSuccessfulResponseBodyStub($firstName = 'FirstName', $lastName = 'LastName', $email = 'a@b.c', $username = 'theusername', $password = '');

        //                     httpResponseBody     expectedExceptionMessage
        yield 'first_name' => [$firstNameMissing , 'Invalid `first_name`'];
        yield 'last_name'  => [$lastNameMissing  , 'Invalid `last_name`'];
        yield 'email'      => [$emailMissing     , 'Invalid `email`'];
        yield 'username'   => [$usernameMissing  , 'Invalid `username`'];
        yield 'password'   => [$passwordMissing  , 'Invalid `password`'];
        yield 'empty'      => [''                , 'No response received'];
    }

    /** @dataProvider provideUnexpectedResponses */
    public function test_ParseHttpResponse_ShouldThrowException_IfApiRespondWithUnexpectedResponse(
        string $httpResponseBody,
        string $expectedExceptionMessage
    ): void {
        $httpResponse = new FakeHttpResponse($httpStatusCode = mt_rand(200, 500), $httpResponseBody);

        $this->expectException(DataProviderException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        RandomUserMeHttpResponseParser::parseHttpResponse($httpResponse);
    }
}
