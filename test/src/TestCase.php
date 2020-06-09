<?php

namespace Test\LoremUserGenerator;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        self::defineEnvironmentConstants();
    }

    private static function defineEnvironmentConstants(): void
    {
        define('ABSPATH', 1);
    }
}
