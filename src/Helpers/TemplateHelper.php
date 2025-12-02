<?php
declare(strict_types=1);

namespace NXW\PageBuilder\Helpers;

/**
 * Template Helper - Renders view templates
 */
class TemplateHelper
{
    /**
     * Render template
     *
     * @param string $template Template name (without .php)
     * @param array $data Data to pass to template
     * @param bool $return Return output instead of echo
     * @return string|null
     */
    public static function render(string $template, array $data = [], bool $return = false): ?string
    {
        $templateFile = NXW_PAGE_BUILDER_PLUGIN_DIR . 'views/' . $template . '.php';

        if (!file_exists($templateFile)) {
            if ($return) {
                return '';
            }
            echo '<p>' . esc_html__('Template not found: ', 'nxw-page-builder') . esc_html($template) . '</p>';
            return null;
        }

        // Extract data to variables
        extract($data, EXTR_SKIP);

        if ($return) {
            ob_start();
            include $templateFile;
            return ob_get_clean();
        }

        include $templateFile;
        return null;
    }

    /**
     * Escape output
     */
    public static function escape($value): string
    {
        return esc_html($value);
    }

    /**
     * Escape attribute
     */
    public static function escapeAttr($value): string
    {
        return esc_attr($value);
    }

    /**
     * Escape URL
     */
    public static function escapeUrl(string $url): string
    {
        return esc_url($url);
    }
}

