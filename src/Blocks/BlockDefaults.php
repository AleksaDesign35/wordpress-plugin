<?php
declare(strict_types=1);

namespace NXW\PageBuilder\Blocks;

/**
 * Block Defaults - Default values for all blocks
 * This will be made dynamic later
 */
class BlockDefaults
{
    /**
     * Get default features for Feature block
     * 
     * @return array Default features array
     */
    public static function getFeatureDefaults(): array
    {
        return [
            [
                'number' => '01',
                'icon' => NXW_PAGE_BUILDER_PLUGIN_URL . 'src/New Blocks/assets/img/feature-icon/icon1.svg',
                'title' => 'Dedicated Team Member',
                'link' => '#',
                'description' => 'We are dedicated to helping businesses unlock ways',
                'isActive' => false,
            ],
            [
                'number' => '02',
                'icon' => NXW_PAGE_BUILDER_PLUGIN_URL . 'src/New Blocks/assets/img/feature-icon/icon2.svg',
                'title' => 'Quality Business Consulting Services',
                'link' => '#',
                'description' => 'We are dedicated to helping businesses unlock ways',
                'isActive' => true,
            ],
            [
                'number' => '03',
                'icon' => NXW_PAGE_BUILDER_PLUGIN_URL . 'src/New Blocks/assets/img/feature-icon/icon3.svg',
                'title' => 'Fast Growing Sells Development',
                'link' => '#',
                'description' => 'We are dedicated to helping businesses unlock ways',
                'isActive' => false,
            ],
            [
                'number' => '04',
                'icon' => NXW_PAGE_BUILDER_PLUGIN_URL . 'src/New Blocks/assets/img/feature-icon/icon4.svg',
                'title' => 'Innovative Ideas Customer Assistance',
                'link' => '#',
                'description' => 'We are dedicated to helping businesses unlock ways',
                'isActive' => false,
            ],
        ];
    }

    /**
     * Get default title for Feature block
     * 
     * @return string Default title
     */
    public static function getFeatureTitle(): string
    {
        return 'Unlocking Growth Exploring the Powerhouse Features of Our Business Solutions';
    }

    /**
     * Get default settings for a block
     * 
     * @param string $blockName Block name (e.g., 'feature', 'hero')
     * @return array Default settings array
     */
    public static function getBlockDefaults(string $blockName): array
    {
        $methodName = 'get' . ucfirst($blockName) . 'Defaults';
        
        if (method_exists(self::class, $methodName)) {
            return self::$methodName();
        }
        
        return [];
    }

    /**
     * Get all defaults for a block (title, items, etc.)
     * 
     * @param string $blockName Block name
     * @return array Complete defaults array
     */
    public static function getAllBlockDefaults(string $blockName): array
    {
        $defaults = [];
        
        // Get title if method exists
        $titleMethod = 'get' . ucfirst($blockName) . 'Title';
        if (method_exists(self::class, $titleMethod)) {
            $defaults['title'] = self::$titleMethod();
        }
        
        // Get items/defaults if method exists
        $defaultsMethod = 'get' . ucfirst($blockName) . 'Defaults';
        if (method_exists(self::class, $defaultsMethod)) {
            $items = self::$defaultsMethod();
            if (!empty($items)) {
                // Determine the key based on block name
                $key = $blockName === 'feature' ? 'features' : 'items';
                $defaults[$key] = $items;
            }
        }
        
        return $defaults;
    }
}

