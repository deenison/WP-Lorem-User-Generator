<?php

namespace LoremUserGenerator\App\Modules\AddMultipleUsers\Http\Response;

if (!defined('ABSPATH')) exit;

use LoremUserGenerator\App\Http\Response\HttpResponse;

final class SuccessfulSaveHttpResponse implements HttpResponse
{
    public function jsonSerialize()
    {
        return [
            'status' => 'success',
            'redirect_url' => admin_url('users.php'),
        ];
    }
}
