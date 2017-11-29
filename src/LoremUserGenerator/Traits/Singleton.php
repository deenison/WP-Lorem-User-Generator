<?php
namespace LoremUserGenerator\Traits;

// Prevent direct access.
if (!defined('ABSPATH')) exit;

/**
 * @package     LoremUserGenerator
 * @subpackage  Traits
 * @author      Denison Martins <https://github.com/deenison>
 * @copyright   Copyright (c) 2017 Lorem User Generator
 * @license     GPL-3
 * @since       1.0.0
 */
trait Singleton
{
    /**
     * @since   1.0.0
     * @access  private
     * @static
     *
     * @var     \ReflectionClass    $instance   The singleton class's instance.
     */
    private static $instance = null;

    /**
     * Initializes the singleton if it's not loaded yet.
     *
     * @since   1.0.0
     * @static
     * @final
     *
     * @uses    \ReflectionClass
     */
    final public static function instantiate()
    {
        if (empty(self::$instance)) {
            $reflection = new \ReflectionClass(__CLASS__);
            self::$instance = $reflection->newInstanceArgs(func_get_args());
        }
    }

    /**
     * Retrieve the singleton instance.
     * If the singleton it's not loaded, it will be initialized first.
     *
     * @since   1.0.0
     * @static
     *
     * @return  \ReflectionClass
     */
    public static function getInstance()
    {
        // Ensure the singleton is loaded.
        self::instantiate();

        return self::$instance;
    }

    /**
     * Prevent the class instance being serialized.
     *
     * @since   1.0.0
     * @final
     *
     * @throws  \Exception
     */
    final public function __sleep()
    {
        throw new \Exception("You cannot serialize a singleton.");
    }

    /**
     * Prevent the class instance being unserialized.
     *
     * @since   1.0.0
     * @final
     *
     * @throws  \Exception
     */
    final public function __wakeup()
    {
        throw new \Exception("You cannot unserialize a singleton.");
    }

    /**
     * Prevent the class instance being cloned.
     *
     * @since   1.0.0
     * @final
     */
    final public function __clone()
    {
        // Do nothing.
    }
}
