<?php

namespace LoremUserGenerator\App\Http\Response;

final class FailedHttpResponse implements HttpResponse
{
    private const RESPONSE_STATUS = 'fail';
    private const BAD_FAIL_MESSAGE_EXCEPTION_MESSAGE = 'A fail message must be provided';

    /** @var string */
    private $failMessage;

    public function __construct(string $failMessage)
    {
        self::isNotEmptyOrCry($failMessage, self::BAD_FAIL_MESSAGE_EXCEPTION_MESSAGE);
        $this->failMessage = $failMessage;
    }

    public function jsonSerialize()
    {
        return [
            'status' => self::RESPONSE_STATUS,
            'error' => $this->failMessage,
        ];
    }

    private static function isNotEmptyOrCry(string $subject, string $exceptionMessage): void {
        if (empty($subject)) {
            throw new \InvalidArgumentException($exceptionMessage);
        }
    }
}
