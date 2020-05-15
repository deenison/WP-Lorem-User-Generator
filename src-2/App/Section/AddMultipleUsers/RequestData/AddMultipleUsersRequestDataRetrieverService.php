<?php

namespace LoremUserGenerator\App\Section\AddMultipleUsers\RequestData;

final class AddMultipleUsersRequestDataRetrieverService
{
    private const QUANTITY_REQUEST_KEY = 'qty';

    private function __construct()
    {
    }

    public static function retrieveQuantity(): int
    {
        $unsanitizedQuantity = $_GET[self::QUANTITY_REQUEST_KEY] ?? '';
        $sanitizedQuantity = AddMultipleUsersRequestDataSanitizerService::sanitizeQuantity($unsanitizedQuantity);
        AddMultipleUsersRequestDataValidatorService::isQuantityValidOrCry($sanitizedQuantity);
        return $sanitizedQuantity;
    }
}
