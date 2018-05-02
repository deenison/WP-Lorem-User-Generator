<?php
/**
 * Plugin Name: Lorem User Generator
 * Description: A straightforward way to add randomly generated users to your WordPress.
 * Plugin URI: https://github.com/deenison/wp-lorem-user-generator
 * Author: Denison Martins
 * Author URI: https://github.com/deenison
 * Version: 1.3.0
 * Text Domain: lorem-user-generator
 * Domain Path: /languages
 */

// Prevent direct access.
// if (!defined('ABSPATH')) {
//     exit;
// }

/**
 * Plugin current version.
 *
 * @since   1.0.0
 */
define('LUG_VERSION', '1.3.0');

/**
 * Plugin slug.
 *
 * @since   1.0.0
 */
define('LUG_SLUG', 'lorem-user-generator');

/**
 * Plugin bootstrap file.
 * I.e.: "the-plugin/the-plugin.php"
 *
 * @since   1.0.0
 */
define('LUG_PLUGIN_BOOTSTRAP', LUG_SLUG . '/' . LUG_SLUG . '.php');

/**
 * Plugin base path.
 *
 * @since   1.0.0
 */
define('LUG_PATH', plugin_dir_path(__FILE__));

/**
 * Plugin base URL.
 *
 * @since   1.0.0
 */
define('LUG_BASE_URL', plugin_dir_url(__FILE__));

/**
 * Plugin name without spaces.
 *
 * @since   1.0.0
 */
define('LUG_NAME', 'LoremUserGenerator');

/**
 * Base namespace.
 *
 * @since   1.0.0
 */
define('LUG_NAMESPACE', '\\' . LUG_NAME . '\\');

/**
 * API URL we use to fetch random generated users.
 *
 * @since   1.0.0
 */
define('LUG_RANDOM_API_URL', "https://randomuser.me/api");

// Load all plugin source files.
// require_once LUG_PATH . 'autoloader.php';
