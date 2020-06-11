<?php

namespace LoremUserGenerator\App\Http\Response;

if (!defined('ABSPATH')) exit;

final class ErrorHttpResponse implements HttpResponse
{
    private const RESPONSE_STATUS = 'error';
    private const BAD_ERROR_MESSAGE_EXCEPTION_MESSAGE = 'An error message must be provided';

    /** @var string */
    private $errorMessage;

    public function __construct(string $errorMessage)
    {
        self::isNotEmptyOrCry($errorMessage, self::BAD_ERROR_MESSAGE_EXCEPTION_MESSAGE);
        $this->errorMessage = $errorMessage;
    }

    public function jsonSerialize()
    {
        return [
            'status' => self::RESPONSE_STATUS,
            'error' => $this->errorMessage,
        ];
    }

    private static function isNotEmptyOrCry(string $subject, string $exceptionMessage): void {
        if (empty($subject)) {
            throw new \InvalidArgumentException($exceptionMessage);
        }
    }
}
