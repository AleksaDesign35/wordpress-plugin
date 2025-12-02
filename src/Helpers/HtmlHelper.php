<?php
declare(strict_types=1);

namespace NXW\PageBuilder\Helpers;

/**
 * HTML Helper - Generates HTML elements
 */
class HtmlHelper
{
    /**
     * Generate input field
     */
    public static function input(
        string $type,
        string $name,
        ?string $value = null,
        array $attributes = []
    ): string {
        $attrs = array_merge([
            'type' => $type,
            'name' => $name,
            'value' => $value ?? '',
        ], $attributes);

        return self::tag('input', '', $attrs);
    }

    /**
     * Generate select dropdown
     */
    public static function select(
        string $name,
        array $options,
        ?string $selected = null,
        array $attributes = []
    ): string {
        $attrs = array_merge(['name' => $name], $attributes);
        $optionsHtml = '';

        foreach ($options as $value => $label) {
            $selectedAttr = ($value == $selected) ? 'selected' : '';
            $optionsHtml .= sprintf(
                '<option value="%s" %s>%s</option>',
                esc_attr($value),
                $selectedAttr,
                esc_html($label)
            );
        }

        return self::tag('select', $optionsHtml, $attrs);
    }

    /**
     * Generate HTML tag
     */
    public static function tag(
        string $tag,
        string $content = '',
        array $attributes = []
    ): string {
        $attrs = '';
        foreach ($attributes as $key => $value) {
            if ($value !== null && $value !== '') {
                $attrs .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
            }
        }

        if (in_array(strtolower($tag), ['input', 'img', 'br', 'hr', 'meta', 'link'], true)) {
            return sprintf('<%s%s />', $tag, $attrs);
        }

        return sprintf('<%s%s>%s</%s>', $tag, $attrs, $content, $tag);
    }

    /**
     * Generate label
     */
    public static function label(string $for, string $text, array $attributes = []): string
    {
        $attrs = array_merge(['for' => $for], $attributes);
        return self::tag('label', esc_html($text), $attrs);
    }
}

