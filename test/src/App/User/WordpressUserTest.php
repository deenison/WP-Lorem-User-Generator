<?php

namespace Test\LoremUserGenerator\App\User;

use LoremUserGenerator\App\User\WordpressUser;
use LoremUserGenerator\User\UserEntity;
use PHPUnit\Framework\Assert;
use Test\LoremUserGenerator\TestCase;

class WordpressUserTest extends TestCase
{
    public function testFromUser(): void
    {
        $user = UserEntity::builder()
            ->withFirstName('Jack')
            ->withLastName('Sheppard')
            ->withEmail('jack.sheppard@oceanic.sbx')
            ->withUsername('jack')
            ->withPassword('jacob')
            ->build();

        $wpUser = WordpressUser::fromUser($user);

        Assert::assertEquals('Jack', $wpUser->getFirstName());
        Assert::assertEquals('Sheppard', $wpUser->getLastName());
        Assert::assertEquals('jack.sheppard@oceanic.sbx', $wpUser->getEmail());
        Assert::assertEquals('jack', $wpUser->getUsername());
        Assert::assertEquals('jacob', $wpUser->getPassword());
    }

    public function testToArray(): void
    {
        $user = UserEntity::builder()
            ->withFirstName('Jack')
            ->withLastName('Sheppard')
            ->withEmail('jack.sheppard@oceanic.sbx')
            ->withUsername('jack')
            ->withPassword('jacob')
            ->build();

        $wpUser = WordpressUser::fromUser($user);

        $wpUserAsArray = $wpUser->toArray();

        $expectedArray = [
            'first_name' => 'Jack',
            'last_name' => 'Sheppard',
            'user_email' => 'jack.sheppard@oceanic.sbx',
            'user_login' => 'jack',
            'user_pass' => 'jacob',
        ];

        Assert::assertEquals($expectedArray, $wpUserAsArray);
    }
}
