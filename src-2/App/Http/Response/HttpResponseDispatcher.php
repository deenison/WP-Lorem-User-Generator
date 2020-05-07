<?php

namespace LoremUserGenerator\App\Http\Response;

use LoremUserGenerator\User\UserEntity;

final class HttpResponseDispatcher
{
    private const CONTENT_TYPE_JSON = 'Content-Type: application/json';

    private function __construct()
    {
    }

    public static function dispatchSuccessfulResponse(UserEntity $user): void
    {
        $successfulResponse = new SuccessfulHttpResponse($user);
        self::dispatch($successfulResponse);
    }

    public static function dispatchErrorResponse(string $errorMessage): void
    {
        $errorHttpResponse = new ErrorHttpResponse($errorMessage);
        self::dispatch($errorHttpResponse);
    }

    public static function dispatchFailedResponse(string $failMessage): void
    {
        $failedHttpResponse = new FailedHttpResponse($failMessage);
        self::dispatch($failedHttpResponse);
    }

    public static function dispatch(HttpResponse $httpResponse): void
    {
        header(self::CONTENT_TYPE_JSON);
        echo json_encode($httpResponse);
        wp_die();
    }
}
