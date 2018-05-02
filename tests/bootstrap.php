<?php
namespace LoremUserGenerator\Tests;

$_tests_dir = getenv('WP_TESTS_DIR');
if (!$_tests_dir) {
    $_tests_dir = '/tmp/wordpress-tests-lib';
}

require_once dirname(__DIR__) . '/src/lorem-user-generator.php';

// Activates this plugin in WordPress so it can be tested.
$GLOBALS['wp_tests_options'] = array(
    'active_plugins' => array(LUG_PLUGIN_BOOTSTRAP)
);

require_once $_tests_dir . '/includes/functions.php';
require_once 'helper.php';

tests_add_filter('muplugins_loaded', '_manually_load_plugin');

require $_tests_dir . '/includes/bootstrap.php';
