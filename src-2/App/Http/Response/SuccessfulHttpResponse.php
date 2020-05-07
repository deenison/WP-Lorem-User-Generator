<?php

namespace LoremUserGenerator\App\Http\Response;

use LoremUserGenerator\User\UserEntity;

final class SuccessfulHttpResponse implements HttpResponse
{
    private const RESPONSE_STATUS = 'success';

    /** @var UserEntity */
    private $user;

    public function __construct(UserEntity $user)
    {
        $this->user = $user;
    }

    public function jsonSerialize()
    {
        return [
            'status' => self::RESPONSE_STATUS,
            'data' => $this->user,
        ];
    }
}
