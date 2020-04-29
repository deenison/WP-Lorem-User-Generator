<?php
/**
 * Plugin Name: Lorem User Generator
 * Description: A straightforward way to add randomly generated users to your WordPress.
 * Plugin URI: https://github.com/deenison/wp-lorem-user-generator
 * Author: Denison Martins
 * Author URI: https://github.com/deenison
 * Version: 2.0.0-alpha-1.0
 * Text Domain: lorem-user-generator
 * Domain Path: /languages
 */

// Prevent direct access.
if (!defined('ABSPATH')) exit;

define('PLG_LUG_VERSION', uniqid());
define('PLG_LUG_BASE_URL', plugin_dir_url(__FILE__));

require_once 'vendor/autoload.php';

\LoremUserGenerator\App\Bootstrap::start();
