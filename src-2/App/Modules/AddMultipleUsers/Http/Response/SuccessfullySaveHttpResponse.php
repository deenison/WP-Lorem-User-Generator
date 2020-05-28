<?php

namespace LoremUserGenerator\App\Modules\AddMultipleUsers\Http\Response;

use LoremUserGenerator\App\Http\Response\HttpResponse;

final class SuccessfullySaveHttpResponse implements HttpResponse
{
    public function jsonSerialize()
    {
        return [
            'status' => 'success',
            'redirect_url' => admin_url('users.php'),
        ];
    }
}
