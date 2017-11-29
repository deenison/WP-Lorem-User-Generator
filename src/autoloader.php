<?php
/**
 * File that loads the plugin autoloader file.
 *
 * @package     LoremUserGenerator
 * @author      Denison Martins <https://github.com/deenison>
 * @copyright   Copyright (c) 2017 Lorem User Generator
 * @license     GPL-3
 * @since       1.0.0
 */

// Prevent direct access.
if (!defined('ABSPATH')) exit;

use \LoremUserGenerator\PSR4\Autoloader;
use \LoremUserGenerator\Plugin;

if (!class_exists(LUG_NAMESPACE . '\\Autoloader')) {
    require_once LUG_PATH . LUG_NAME . '/PSR4/Autoloader.php';
}

// Register namespace.
Autoloader::register(
    LUG_NAMESPACE,
    LUG_PATH . LUG_NAME
);

// Initializes the plugin.
Plugin::instantiate();
