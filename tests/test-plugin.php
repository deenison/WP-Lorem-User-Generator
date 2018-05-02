<?php
namespace LoremUserGenerator\Tests;

class PluginTest extends WP_UnitTestCase
{
    public function test_plugin_activated()
    {
        $this->assertTrue(is_plugin_active(PLUGIN_PATH));
    }
}
