<?php

namespace Test\LoremUserGenerator;

use LoremUserGenerator\Foo;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class FooTest extends TestCase {

    public function test_foo_sum(): void {
        $sum = Foo::sum(3, 5);
        Assert::assertEquals(8, $sum);
    }
}
