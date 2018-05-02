<?php
namespace LoremUserGenerator\Tests;

$_tests_dir = getenv('WP_TESTS_DIR');
if (!$_tests_dir) {
    $_tests_dir = '/tmp/wordpress-tests-lib';
}

require_once dirname(__DIR__) . '/src/constants.php';

// Activates this plugin in WordPress so it can be tested.
$GLOBALS['wp_tests_options'] = array(
    'active_plugins' => array('src/' . LUG_SLUG . '.php')
);

require_once $_tests_dir . '/includes/functions.php';
require_once 'helper.php';

tests_add_filter('muplugins_loaded', array('\\LoremUserGenerator\\Tests\\PluginTest', '_manually_load_plugin'));

require $_tests_dir . '/includes/bootstrap.php';
