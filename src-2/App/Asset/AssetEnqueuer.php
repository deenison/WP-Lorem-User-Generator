<?php

namespace LoremUserGenerator\App\Asset;

if (!defined('ABSPATH')) exit;

final class AssetEnqueuer
{
    private function __construct()
    {
    }

    public static function enqueueScript(string $identifier, string $scriptFileName, array $dependencies = []): void
    {
        $scriptFilePath = self::composeScriptFilePath($scriptFileName);
        wp_enqueue_script($identifier, $scriptFilePath, $dependencies, PLG_LUG_VERSION);
    }

    public static function enqueueStylesheetFile(string $identifier, string $scriptFileName, array $dependencies = []): void
    {
        $scriptFilePath = self::composeStylesheetFilePath($scriptFileName);
        wp_enqueue_style($identifier, $scriptFilePath, $dependencies, PLG_LUG_VERSION);
    }

    private static function composeScriptFilePath(string $scriptFileName): string
    {
        return PLG_LUG_BASE_URL .'assets/js/'. $scriptFileName .'.js';
    }

    private static function composeStylesheetFilePath(string $scriptFileName): string
    {
        return PLG_LUG_BASE_URL .'assets/css/'. $scriptFileName .'.css';
    }
}
