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
if (!defined('ABSPATH')) {
    exit;
}

require_once 'constants.php';

// Load all plugin source files.
require_once LUG_PATH . 'autoloader.php';
