<?php
declare(strict_types=1);

namespace NXW\PageBuilder\Helpers;

/**
 * Array Helper - Array manipulation utilities
 */
class ArrayHelper
{
    /**
     * Get value from array with default
     */
    public static function get(array $array, string $key, $default = null)
    {
        return $array[$key] ?? $default;
    }

    /**
     * Check if array has key
     */
    public static function has(array $array, string $key): bool
    {
        return isset($array[$key]) || array_key_exists($key, $array);
    }

    /**
     * Flatten multidimensional array
     */
    public static function flatten(array $array): array
    {
        $result = [];
        foreach ($array as $item) {
            if (is_array($item)) {
                $result = array_merge($result, self::flatten($item));
            } else {
                $result[] = $item;
            }
        }
        return $result;
    }

    /**
     * Group array by key
     */
    public static function groupBy(array $array, string $key): array
    {
        $result = [];
        foreach ($array as $item) {
            if (is_array($item) && isset($item[$key])) {
                $result[$item[$key]][] = $item;
            }
        }
        return $result;
    }
}

