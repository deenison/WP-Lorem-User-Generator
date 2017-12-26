<?php
namespace LoremUserGenerator\PSR4;

// Prevent direct access.
if (!defined('ABSPATH')) exit;

/**
 * @package     LoremUserGenerator
 * @subpackage  PSR4
 * @copyright   Copyright (c) 2017 Lorem User Generator
 * @license     GPL-3
 * @since       1.0.0
 *
 * @see         http://www.php-fig.org/psr/psr-4
 */
class Autoloader
{
    /**
     * Associative array where the key is a namespace prefix and the value
     * is an array of base directories for classes in that namespace.
     *
     * @since   1.0.0
     * @access  protected
     * @static
     *
     * @var     array
     */
    protected static $prefixes = array();

    /**
     * Associative array of prefixes for loading specialized camelCase classes
     * where Uppercase letters in the class name indicate directory structure
     *
     * @since   1.0.0
     * @access  protected
     * @static
     *
     * @var     array
     */
    protected static $camelPrefixes = array();

    /**
     * @since   1.0.0
     * @access  protected
     * @static
     *
     * @var     AutoLoader
     */
    protected static $instance = null;

    /**
     * Register a new loader.
     *
     * @since   1.0.0
     * @access  protected
     * @static
     *
     * @param   string $method  The method name which will be called.
     * @return  void
     */
    protected static function registerLoader($method)
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        spl_autoload_register(array(static::$instance, $method));
    }

    /**
     * Register a psr4 namespace
     *
     * @since   1.0.0
     * @static
     *
     * @param   string  $prefix   The namespace prefix.
     * @param   string  $baseDir  A base directory for class files in the namespace.
     * @param   bool    $prepend  If true, prepend the base directory to the stack instead of
     *                            appending it; this causes it to be searched first rather than last.
     *
     * @return  void
     */
    public static function register($prefix = null, $baseDir = null, $prepend = false)
    {
        if ($prefix === null || $baseDir === null) {
            // Recognize old-style instantiations for backward compatibility
            return;
        }

        if (count(self::$prefixes) == 0) {
            // Register function on first call
            static::registerLoader('loadClass');
        }

        // normalize namespace prefix
        $prefix = trim($prefix, '\\') . '\\';

        // normalize the base directory with a trailing separator
        $baseDir = rtrim($baseDir, '\\/') . '/';

        // initialise the namespace prefix array
        if (empty(self::$prefixes[$prefix])) {
            self::$prefixes[$prefix] = array();
        }

        // retain the base directory for the namespace prefix
        if ($prepend) {
            array_unshift(self::$prefixes[$prefix], $baseDir);
        } else {
            array_push(self::$prefixes[$prefix], $baseDir);
        }
    }

    /**
     * Loads the class file for a given class name.
     *
     * @since   1.0.0
     * @access  protected
     * @static
     *
     * @param   string  $class  The fully-qualified class name.
     *
     * @return  mixed           The mapped file name on success, or boolean false on failure.
     */
    protected function loadClass($class)
    {
        $prefixes  = explode('\\', $class);
        $className = '';
        while ($prefixes) {
            $className = array_pop($prefixes) . $className;
            $prefix    = join('\\', $prefixes) . '\\';

            if ($filePath = $this->loadMappedFile($prefix, $className)) {
                return $filePath;
            }
            $className = '\\' . $className;
        }

        // never found a mapped file
        return false;
    }

    /**
     * Load the mapped file for a namespace prefix and class.
     *
     * @since   1.0.0
     * @access  protected
     * @static
     *
     * @param   string  $prefix     The namespace prefix.
     * @param   string  $className  The relative class name.
     *
     * @return  mixed               False if no mapped file can be loaded | path that was loaded
     */
    protected function loadMappedFile($prefix, $className)
    {
        // are there any base directories for this namespace prefix?
        if (isset(self::$prefixes[$prefix]) === false) {
            return false;
        }

        // look through base directories for this namespace prefix
        foreach (self::$prefixes[$prefix] as $baseDir) {
            $path = $baseDir . str_replace('\\', '/', $className) . '.php';

            if (is_file($path)) {
                require_once $path;
                return $path;
            }
        }

        // never found it
        return false;
    }

    /**
     * Register a base directory for classes organized using camelCase.
     * Class names beginning with the prefix will be automatically loaded
     * if there is a matching file in the directory tree starting with $baseDir.
     * File names and directory names are all expected to be lower case.
     *
     * @since   1.0.0
     * @static
     *
     * @param   string  $prefix
     * @param   string  $baseDir
     *
     * @return void
     * @throws \Exception
     */
    public static function registerCamelBase($prefix, $baseDir)
    {
        if (!is_dir($baseDir)) {
            throw new \Exception("Cannot register '{$prefix}'. The requested base directory does not exist!'");
        }

        if (count(self::$camelPrefixes) == 0) {
            // Register function on first call
            static::registerLoader('loadCamelClass');
        }

        if (empty(self::$camelPrefixes[$prefix])) {
            self::$camelPrefixes[$prefix] = $baseDir;
        }
    }

    /**
     * Autoload a class using the camelCase structure
     *
     * @since   1.0.0
     * @access  protected
     *
     * @param   string      $class
     *
     * @return  mixed       Bool/string
     */
    protected function loadCamelClass($class)
    {
        if (!class_exists($class)) {
            foreach (self::$camelPrefixes as $prefix => $baseDir) {
                if (strpos($class, $prefix) === 0) {
                    $parts = preg_split('/(?<=[a-z])(?=[A-Z])/x', substr($class, strlen($prefix)));

                    $file     = strtolower(join('/', $parts));
                    $filePath = $baseDir . '/' . $file . '.php';

                    if (is_file($filePath)) {
                        require_once $filePath;
                        return $filePath;
                    }
                }
            }
        }

        // No file found.
        return false;
    }
}
