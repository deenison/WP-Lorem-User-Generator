<?php

namespace LoremUserGenerator\App\Template;

final class TemplateRenderer
{
    private function __construct()
    {
    }

    public static function render(string $templateFileName, array $context = []): void
    {
        $templateFullFilePath = self::composeTemplateFileFullPath($templateFileName);
        self::validateTemplateFileOrCry($templateFullFilePath, $templateFileName);
        self::validateContextArrayScalarsOrCry($context);

        echo self::renderTemplateContentsInBuffer($templateFullFilePath, $context);
    }

    private static function composeTemplateFileFullPath(string $templateFileName): string
    {
        $templatesBasePath = dirname(dirname(dirname(dirname(__FILE__))));
        return "{$templatesBasePath}/templates/{$templateFileName}.php";
    }

    private static function validateTemplateFileOrCry(string $templateFullFilePath, string $templateFileName): void
    {
        if (
            !file_exists($templateFullFilePath)
            || is_dir($templateFullFilePath)
            || !is_readable($templateFullFilePath)
        ) {
            throw new \InvalidArgumentException('Template not found: `'. $templateFileName .'`');
        }
    }

    private static function validateContextArrayScalarsOrCry(array $context): void
    {
        foreach ($context as $key => $value) {
            if (is_scalar($value)) {
                continue;
            }

            if (is_array($value)) {
                self::validateContextArrayScalarsOrCry($value);
            } else {
                throw new \InvalidArgumentException('The following param must be scalar: `'. $key .'`');
            }
        }
    }

    private static function renderTemplateContentsInBuffer(string $templateFullFilePath, array $context): string {
        ob_start();
        foreach ($context as $varName => $varValue) {
            $$varName = $varValue;
        }

        include $templateFullFilePath;

        $templateContents = ob_get_contents();
        ob_end_clean();

        return $templateContents;
    }
}
