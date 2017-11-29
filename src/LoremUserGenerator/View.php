<?php
namespace LoremUserGenerator;

// Prevent direct access.
if (!defined('ABSPATH')) exit;

use \LoremUserGenerator\Plugin;
use \LoremUserGenerator\Helper;

/**
 * @package     LoremUserGenerator
 * @author      Denison Martins <https://github.com/deenison>
 * @copyright   Copyright (c) 2017 Lorem User Generator
 * @license     GPL-3
 * @since       1.0.0
 * @final
 */
final class View
{
    /**
     * Render a given view file located on /templates folder.
     *
     * @since   1.0.0
     * @static
     *
     * @throws  \Exception if $viewName doesn't exists.
     *
     * @param   string  $viewName   Template file name.
     * @param   array   $args       Arguments to be passed to the template as variables
     * @param   bool    $asHtml     Either the template will be rendered or returned as a string.
     *
     * @param   string
     */
    public static function render($viewName, $args = array(), $asHtml = false)
    {
        if (empty($viewName)) {
            return;
        }

        $asHtml = (bool)$asHtml;

        $templatePath = LUG_PATH . 'templates/' . $viewName . '.php';

        if (!file_exists($templatePath)) {
            throw new \Exception('Template file "' . $viewName . '" not found.');
        }

        $args = is_object($args) ? get_object_vars($args) : $args;
        if (!empty($args)) {
            foreach ($args as $varName => $varValue) {
                $$varName = $varValue;
            }
        }

        do_action('lorem-user-generator:onBeforeRenderView', $viewName, $args, $asHtml);

        $templateHtml = null;

        if ($asHtml) {
            ob_start();
            include $templatePath;
            $templateHtml = ob_get_contents();
            ob_end_clean();
        } else {
            include $templatePath;
        }

        do_action('lorem-user-generator:onAfterRenderView', $viewName, $args, $asHtml, $templateHtml);

        if ($asHtml) {
            return $templateHtml;
        }
    }
}
