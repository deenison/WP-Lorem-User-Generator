<?php
namespace LoremUserGenerator;

// Prevent direct access.
if (!defined('ABSPATH')) exit;

use \LoremUserGenerator\Traits\Singleton;
use \LoremUserGenerator\Helper;
use \LoremUserGenerator\View;

/**
 * @package     LoremUserGenerator
 * @author      Denison Martins <https://github.com/deenison>
 * @copyright   Copyright (c) 2017 Lorem User Generator
 * @license     GPL-3
 * @since       1.0.0
 * @final
 */
final class Plugin
{
    use Singleton;

    /**
     * Store the current namespace so it can be reused on various methods.
     *
     * @since   1.0.0
     * @access  private
     * @static
     *
     * @var     string   $namespace
     */
    private static $namespace;

    /**
     * Store the controller's namespace so it can be reused on various methods.
     *
     * @since   1.0.0
     * @access  private
     * @static
     *
     * @var     string   $controllerNamespace
     */
    private static $controllerNamespace;

    /**
     * Class constructor.
     *
     * @since   1.0.0
     */
    public function __construct()
    {
        if (!is_admin()) {
            return;
        }

        self::$namespace = get_class(self::$instance);
        self::$controllerNamespace = LUG_NAMESPACE . 'Controller';

        $this->loadLanguageDomain();
        $this->attachHooks();
    }

    /**
     * Load language domain.
     *
     * @since   1.0.0
     * @access  private
     */
    private function loadLanguageDomain()
    {
        $activeLocale = apply_filters('plugin_locale', get_locale(), LUG_SLUG);

        load_textdomain(LUG_SLUG, sprintf('%s/%s/%2$s-%3$s.mo', WP_LANG_DIR, LUG_SLUG, $activeLocale));
        load_plugin_textdomain(LUG_SLUG, false, LUG_SLUG . '/languages');
    }

    /**
     * Attach all required filters and actions to its correspondent endpoints.
     *
     * @since   1.0.0
     * @access  private
     */
    private function attachHooks()
    {
        add_action('admin_menu', array(self::$namespace, 'registerAdminMenus'));
        add_action('admin_init', array(self::$namespace, 'registerSettings'));

        add_action('admin_enqueue_scripts', array(self::$namespace, 'enqueueScripts'));
        add_action('admin_enqueue_scripts', array(self::$namespace, 'enqueueStyles'));

        add_action('wp_ajax_luser:generate_users', array(self::$controllerNamespace, 'generateRandomUsers'));
        add_action('wp_ajax_luser:add_user', array(self::$controllerNamespace, 'addUser'));
    }

    /**
     * Register all plugin menus to admin area.
     *
     * @since   1.0.0
     * @static
     */
    public static function registerAdminMenus()
    {
        $pluginName = __('Lorem User Generator', 'lorem-user-generator');

        // Register a menu link for the plugin.
        add_submenu_page(
            'users.php',
            $pluginName,
            $pluginName,
            'create_users',
            'lorem-user-generator',
            array(self::$controllerNamespace, 'renderPluginPage')
        );

        // Register a link for the plugin as subpage of Settings.
        add_options_page(
            __('Lorem User Generator Settings', 'lorem-user-generator'),
            $pluginName,
            'manage_options',
            'lorem-user-generator_settings',
            array(self::$controllerNamespace, 'renderSettingsPage')
        );
    }

    /**
     * Register all plugin options.
     *
     * @since   1.0.0
     * @static
     */
    public static function registerSettings()
    {
        register_setting(LUG_SLUG, 'lorem-user-generator:default_user_role', array(
            'type'        => 'string',
            'description' => __('User role that will be pre selected on the results table when generating dummy users data.', 'lorem-user-generator'),
            'default'     => get_option('default_role')
        ));
    }

    /**
     * Enqueue all scripts used by the plugin.
     *
     * @since   1.0.0
     * @static
     */
    public static function enqueueScripts()
    {
        if (self::canRunOnCurrentPage()) {
            wp_enqueue_script('luser-select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js', array('jquery'), LUG_VERSION);
            wp_enqueue_script(LUG_SLUG, LUG_BASE_URL . 'assets/js/lorem-user-generator.js', array('jquery'), LUG_VERSION);
            wp_localize_script(LUG_SLUG, '$l', array(
                'LB_GENERATING'                => __('Generating...', 'lorem-user-generator'),
                'MSG_RESULTS_WILL_APPEAR_HERE' => __('Results will appear here...', 'lorem-user-generator'),
                'LB_GENERATE'                  => __('Generate', 'lorem-user-generator'),
                'LB_RESULTS'                   => _x('Results (%d)', '%d: Number of results', 'lorem-user-generator'),
                'LB_ADDING'                    => __('Adding...', 'lorem-user-generator'),
                'MSG_UNDOCUMMENTED_ERROR'      => __('Undocummented error.', 'lorem-user-generator'),
                'LB_EDIT'                      => __('Edit'),
                'LB_HIDE_FILTERS'              => __('Hide Advanced Filters', 'lorem-user-generator'),
                'LB_SHOW_FILTERS'              => __('Show Advanced Filters', 'lorem-user-generator'),
                'MSG_ARE_YOU_SURE'             => __('Are you sure?', 'lorem-user-generator')
            ));
        }
    }

    /**
     * Enqueue all stylesheets used by the plugin.
     *
     * @since   1.0.0
     * @static
     */
    public static function enqueueStyles()
    {
        if (self::canRunOnCurrentPage()) {
            wp_enqueue_style('luser-select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css', array(), LUG_VERSION);
            wp_enqueue_style('luser', LUG_BASE_URL . 'assets/css/lorem-user-generator.css', array('luser-select2'), LUG_VERSION);
        }
    }

    /**
     * Check if wether the request is under the plugin scope.
     * Used to bail methods if needed.
     *
     * @since   1.0.0
     * @static
     */
    public static function canRunOnCurrentPage()
    {
        global $pagenow;

        // Bail the method if user is outside the plugin scope.
        if ($pagenow !== 'users.php'
            || !isset($_GET['page'])
            || $_GET['page'] !== LUG_SLUG
        ) {
            return false;
        }

        return true;
    }
}
