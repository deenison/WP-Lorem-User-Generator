<?php

namespace Test\LoremUserGenerator\App\User;

use LoremUserGenerator\App\User\WordpressUser;
use LoremUserGenerator\App\User\WordpressUserRepository;
use LoremUserGenerator\Persistence\PersistenceServiceException;
use LoremUserGenerator\User\UserEntity;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Test\LoremUserGenerator\App\Persistence\FakePersistenceService;

class WordpressUserRepositoryTest extends TestCase
{
    public function testStore(): void
    {
        $user = self::buildFakeUser();

        $persistenceService = new FakePersistenceService();
        $persistenceService->stubSuccessfulResult();

        $repository = new WordpressUserRepository($persistenceService);
        $result = $repository->store($user);

        Assert::assertTrue($result);
    }

    public function testStore_ShouldThrowException_IfInsertionFails(): void
    {
        $user = self::buildFakeUser();

        $persistenceService = new FakePersistenceService();
        $persistenceService->stubFailedResult($errorMessage = 'ops!');

        $repository = new WordpressUserRepository($persistenceService);

        $this->expectException(PersistenceServiceException::class);
        $this->expectExceptionMessage('ops!');

        $repository->store($user);
    }

    private static function buildFakeUser(): WordpressUser
    {
        $user = UserEntity::builder()
            ->withFirstName('Jack')
            ->withLastName('Sheppard')
            ->withEmail('jack.sheppard@oceanic.sbx')
            ->withUsername('jack')
            ->withPassword('jacob')
            ->build();

        return WordpressUser::fromUser($user);
    }
}
