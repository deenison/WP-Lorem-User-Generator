<?php

namespace LoremUserGenerator\DataProvider\Provider\RandomUserMe;

use GuzzleHttp\Psr7\Request;
use LoremUserGenerator\Core\Http\HttpRequestBuilderInterface;
use Psr\Http\Message\RequestInterface;

final class RandomUserMeHttpRequestBuilder implements HttpRequestBuilderInterface
{
    private const HTTP_METHOD_GET = 'GET';
    private const API_URI = 'https://randomuser.me/api/?inc=name,login,email';

    public function build(): RequestInterface
    {
        return new Request(self::HTTP_METHOD_GET, self::API_URI);
    }
}
