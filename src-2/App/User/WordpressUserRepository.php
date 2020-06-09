<?php

namespace LoremUserGenerator\App\User;

if (!defined('ABSPATH')) exit;

use LoremUserGenerator\Persistence\PersistenceServiceException;
use LoremUserGenerator\Persistence\PersistenceServiceInterface;

final class WordpressUserRepository
{
    /** @var PersistenceServiceInterface */
    private $persistenceService;

    public function __construct(PersistenceServiceInterface $persistenceService)
    {
        $this->persistenceService = $persistenceService;
    }

    public function store(WordpressUser $user): bool
    {
        $insertionResult = $this->persistenceService->insertUser($user->toArray());
        if (is_int($insertionResult)) {
            return true;
        }

        throw new PersistenceServiceException('Unexpected result: `'. json_encode($insertionResult) .'`');
    }
}
