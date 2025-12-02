<?php
declare(strict_types=1);

namespace NXW\PageBuilder\Helpers;

/**
 * Sanitizer - Input sanitization utilities
 */
class Sanitizer
{
    /**
     * Sanitize text
     */
    public static function sanitizeText(string $text): string
    {
        return sanitize_text_field($text);
    }

    /**
     * Sanitize textarea
     */
    public static function sanitizeTextarea(string $text): string
    {
        return sanitize_textarea_field($text);
    }

    /**
     * Sanitize URL
     */
    public static function sanitizeUrl(string $url): string
    {
        return esc_url_raw($url);
    }

    /**
     * Sanitize integer
     */
    public static function sanitizeInt($value): int
    {
        return (int) $value;
    }

    /**
     * Sanitize array recursively
     */
    public static function sanitizeArray(array $array): array
    {
        $sanitized = [];
        foreach ($array as $key => $value) {
            $sanitizedKey = sanitize_key($key);
            
            if (is_array($value)) {
                $sanitized[$sanitizedKey] = self::sanitizeArray($value);
            } elseif (is_string($value)) {
                $sanitized[$sanitizedKey] = sanitize_text_field($value);
            } elseif (is_int($value)) {
                $sanitized[$sanitizedKey] = self::sanitizeInt($value);
            } else {
                $sanitized[$sanitizedKey] = $value;
            }
        }
        return $sanitized;
    }

    /**
     * Sanitize HTML (allows certain tags)
     */
    public static function sanitizeHtml(string $html, array $allowedTags = []): string
    {
        if (empty($allowedTags)) {
            $allowedTags = [
                'p' => [],
                'br' => [],
                'strong' => [],
                'em' => [],
                'a' => ['href' => [], 'target' => []],
            ];
        }
        return wp_kses($html, $allowedTags);
    }
}

