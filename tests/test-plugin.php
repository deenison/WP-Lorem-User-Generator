<?php
namespace LoremUserGenerator\Tests;

class PluginTest extends \WP_UnitTestCase
{
    public static function testPluginActivation()
    {
        $this->assertTrue(is_plugin_active(LUG_PLUGIN_BOOTSTRAP));
    }
}
