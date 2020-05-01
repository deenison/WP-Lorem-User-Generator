<?php

namespace Test\LoremUserGenerator\Http;

use Psr\Http\Client\ClientExceptionInterface;

final class FakeHttpClientException extends \RuntimeException implements ClientExceptionInterface
{
}
