<?php

namespace LoremUserGenerator\App\Asset;

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

    private static function composeScriptFilePath(string $scriptFileName): string
    {
        return PLG_LUG_BASE_URL .'assets/js/'. $scriptFileName .'.js';
    }
}
