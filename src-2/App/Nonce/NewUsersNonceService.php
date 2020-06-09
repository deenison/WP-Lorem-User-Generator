<?php

namespace LoremUserGenerator\App\Nonce;

if (!defined('ABSPATH')) exit;

final class NewUsersNonceService
{
    private const ACTION_NAME = 'lorem_user_generator_fetch_multiple_random_data';
    private const NONCE_REQUEST_KEY = 'nonce';

    private function __construct()
    {
    }

    public static function generateNonce(): string {
        return wp_create_nonce(self::ACTION_NAME);
    }

    public static function isNonceInRequestValid(): bool {
        return (int)check_ajax_referer(
            self::ACTION_NAME,
            self::NONCE_REQUEST_KEY,
            false
        ) > 0;
    }
}
