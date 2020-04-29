<?php

namespace Test\LoremUserGenerator\DataProvider\Provider\RandomUserMe;

final class RandomUserMeHttpResponseBodyStubFactory
{
    private function __construct()
    {
    }

    public static function getSuccessfulResponseBodyStub(
        string $firstName,
        string $lastName,
        string $email,
        string $username,
        string $password
    ): string {
        return <<<JSON
{
  "results":[
    {
      "name":{
        "title":"Mr",
        "first":"{$firstName}",
        "last":"{$lastName}"
      },
      "email":"{$email}",
      "login":{
        "uuid":"a2fd2a68-abdc-4109-b367-b7172e26f85a",
        "username":"{$username}",
        "password":"{$password}",
        "salt":"QRZWecMl",
        "md5":"3636875a5b9ac790f22382515719d88e",
        "sha1":"4edf0aabb34413389e727a3d526c16846c674ddb",
        "sha256":"25dbcdfee1f2d4993efd723acbe48e3d77b8c61f275fe77945802b9535ef663a"
      }
    }
  ],
  "info":{
    "seed":"2560177d92b6c4e4",
    "results":1,
    "page":1,
    "version":"1.3"
  }
}
JSON;
    }

    public static function getFailedResponseBodyStub(string $errorMessage): string
    {
        return <<<JSON
{
  "error": "{$errorMessage}"
}
JSON;
    }
}
