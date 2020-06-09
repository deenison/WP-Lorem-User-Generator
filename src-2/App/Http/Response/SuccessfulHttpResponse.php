<?php

namespace LoremUserGenerator\App\Http\Response;

if (!defined('ABSPATH')) exit;

use LoremUserGenerator\User\UserEntity;

final class SuccessfulHttpResponse implements HttpResponse
{
    private const RESPONSE_STATUS = 'success';

    /** @var UserEntity[] */
    private $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }

    public function jsonSerialize()
    {
        return [
            'status' => self::RESPONSE_STATUS,
            'data' => $this->users,
        ];
    }
}
