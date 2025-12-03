<?php
declare(strict_types=1);

namespace NXW\PageBuilder\Helpers;

/**
 * Block Helper - Block-specific utility functions
 * Functions for building classes, styles, and attributes for blocks
 */
class BlockHelper
{
    /**
     * Build section classes for a block
     * 
     * @param string $blockName Block name (e.g., 'feature', 'hero')
     * @param array $settings Block settings/attributes
     * @param array $additionalClasses Additional classes to add
     * @return string Space-separated class string
     */
    public static function buildSectionClasses(
        string $blockName,
        array $settings = [],
        array $additionalClasses = []
    ): string {
        $classes = ['nxw-' . $blockName];
        
        // Add padding modifier if set
        if (!empty($settings['padding'])) {
            $classes[] = 'nxw-' . $blockName . '--padding-' . esc_attr($settings['padding']);
        }
        
        // Add background modifier if set
        if (!empty($settings['backgroundColor'])) {
            $classes[] = 'nxw-' . $blockName . '--bg-custom';
        }
        
        // Add alignment modifier if set
        if (!empty($settings['alignment'])) {
            $classes[] = 'nxw-' . $blockName . '--align-' . esc_attr($settings['alignment']);
        }
        
        // Add custom classes from settings
        if (!empty($settings['customClasses'])) {
            $customClasses = is_array($settings['customClasses']) 
                ? $settings['customClasses'] 
                : explode(' ', $settings['customClasses']);
            $classes = array_merge($classes, $customClasses);
        }
        
        // Add additional classes
        if (!empty($additionalClasses)) {
            $classes = array_merge($classes, $additionalClasses);
        }
        
        return esc_attr(implode(' ', array_filter($classes)));
    }

    /**
     * Build section style attribute
     * 
     * @param array $settings Block settings/attributes
     * @return string Style attribute string (with style="..." or empty)
     */
    public static function buildSectionStyles(array $settings = []): string
    {
        $styles = [];
        
        // Background color
        if (!empty($settings['backgroundColor'])) {
            $styles[] = 'background-color: ' . esc_attr($settings['backgroundColor']);
        }
        
        // Background image
        if (!empty($settings['backgroundImage'])) {
            $bgImageUrl = esc_url($settings['backgroundImage']);
            $styles[] = 'background-image: url(' . $bgImageUrl . ')';
            
            // Background size
            if (!empty($settings['backgroundSize'])) {
                $styles[] = 'background-size: ' . esc_attr($settings['backgroundSize']);
            } else {
                $styles[] = 'background-size: cover';
            }
            
            // Background position
            if (!empty($settings['backgroundPosition'])) {
                $styles[] = 'background-position: ' . esc_attr($settings['backgroundPosition']);
            } else {
                $styles[] = 'background-position: center';
            }
            
            // Background repeat
            if (!empty($settings['backgroundRepeat'])) {
                $styles[] = 'background-repeat: ' . esc_attr($settings['backgroundRepeat']);
            } else {
                $styles[] = 'background-repeat: no-repeat';
            }
        }
        
        // Text color
        if (!empty($settings['textColor'])) {
            $styles[] = 'color: ' . esc_attr($settings['textColor']);
        }
        
        // Padding (inline if needed)
        if (!empty($settings['paddingInline'])) {
            $styles[] = 'padding: ' . esc_attr($settings['paddingInline']);
        }
        
        // Margin (inline if needed)
        if (!empty($settings['marginInline'])) {
            $styles[] = 'margin: ' . esc_attr($settings['marginInline']);
        }
        
        // Custom styles
        if (!empty($settings['customStyles'])) {
            if (is_array($settings['customStyles'])) {
                foreach ($settings['customStyles'] as $property => $value) {
                    $styles[] = esc_attr($property) . ': ' . esc_attr($value);
                }
            } else {
                $styles[] = $settings['customStyles'];
            }
        }
        
        if (empty($styles)) {
            return '';
        }
        
        return ' style="' . implode('; ', $styles) . ';"';
    }

    /**
     * Build section attributes (classes + styles)
     * 
     * @param string $blockName Block name
     * @param array $settings Block settings/attributes
     * @param array $additionalClasses Additional classes to add
     * @return string Complete attributes string for section tag
     */
    public static function buildSectionAttributes(
        string $blockName,
        array $settings = [],
        array $additionalClasses = []
    ): string {
        $classes = self::buildSectionClasses($blockName, $settings, $additionalClasses);
        $styles = self::buildSectionStyles($settings);
        
        $attributes = 'class="' . $classes . '"';
        
        if (!empty($styles)) {
            $attributes .= $styles;
        }
        
        return $attributes;
    }

    /**
     * Get section class settings (for compatibility with other systems)
     * Similar to W4D_Block_Helper::get_section_class_settings
     * 
     * @param array $block Block data
     * @return string Section class attribute
     */
    public static function getSectionClassSettings(array $block): string
    {
        $blockName = $block['name'] ?? 'block';
        $settings = $block['settings'] ?? $block['attributes'] ?? [];
        
        $classes = self::buildSectionClasses($blockName, $settings);
        
        return 'class="' . $classes . '"';
    }

    /**
     * Extract and sanitize block settings
     * 
     * @param array $block Block data
     * @return array Sanitized settings
     */
    public static function extractSettings(array $block): array
    {
        $attributes = $block['attributes'] ?? [];
        $settings = $block['settings'] ?? $attributes;
        
        return $settings;
    }
}

