<?php

namespace Test\LoremUserGenerator\User;

use LoremUserGenerator\User\UserEntity;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class UserEntityTest extends TestCase
{
    public function test_Build_ShouldBuildExpectedUserEntity(): void
    {
        $user = UserEntity::builder()
            ->withFirstName('Jack')
            ->withLastName('Sheppard')
            ->withEmail('jacksheppard@sbx.local')
            ->withUsername('jack')
            ->withPassword('we have to go back')
            ->build();

        Assert::assertEquals('Jack', $user->getFirstName());
        Assert::assertEquals('Sheppard', $user->getLastName());
        Assert::assertEquals('jacksheppard@sbx.local', $user->getEmail());
        Assert::assertEquals('jack', $user->getUsername());
        Assert::assertEquals('we have to go back', $user->getPassword());
    }

    public function test_Instance_ShouldBeJsonSerializable(): void
    {
        $user = UserEntity::builder()
            ->withFirstName('Jack')
            ->withLastName('Sheppard')
            ->withEmail('jacksheppard@sbx.local')
            ->withUsername('jack')
            ->withPassword('we have to go back')
            ->build();

        $userAsJson = json_encode($user);

        $expectedUserAsJsonString = <<<JSON
{
    "first_name": "Jack",
    "last_name": "Sheppard",
    "email": "jacksheppard@sbx.local",
    "username": "jack",
    "password": "we have to go back"
}
JSON;

        Assert::assertJsonStringEqualsJsonString($expectedUserAsJsonString, $userAsJson);
    }
}
