<?php
namespace LoremUserGenerator\Tests;

class PluginTest extends \WP_UnitTestCase
{
    public function testPluginActivation()
    {
        $this->assertTrue(is_plugin_active('src/lorem-user-generator.php'));
    }
}
