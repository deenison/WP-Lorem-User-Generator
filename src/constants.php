<?php
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
define('LUG_PATH', dirname(__FILE__) . '/');

/**
 * Plugin base URL.
 *
 * @since   1.0.0
 */
define('LUG_BASE_URL', plugin_dir_url(__FILE__));
define('LUG_PATH', dirname(__FILE__) . '/');

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
