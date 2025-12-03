<?php
declare(strict_types=1);

namespace NXW\PageBuilder\Blocks;

/**
 * Block Loader - Registers and loads blocks
 */
class BlockLoader
{
    /**
     * Blocks directory
     */
    private string $blocksDir;

    /**
     * Initialize block loader
     */
    public function init(): void
    {
        $this->blocksDir = NXW_PAGE_BUILDER_PLUGIN_DIR . 'src/Blocks/';

        add_action('init', [$this, 'registerBlocks'], 10);
        add_action('wp_enqueue_scripts', [$this, 'enqueueBlockAssets']);
    }

    /**
     * Register all blocks
     */
    public function registerBlocks(): void
    {
        $blockDirs = glob($this->blocksDir . '*/', GLOB_ONLYDIR);

        foreach ($blockDirs as $blockDir) {
            $blockName = basename($blockDir);
            $displayPath = $blockDir . 'nxw-display-' . strtolower($blockName) . '-block.php';
            $blockJsonPath = $blockDir . 'block.json';

            // Register block if nxw-display-{block}-block.php exists (with or without block.json)
            if (file_exists($displayPath)) {
            if (file_exists($blockJsonPath)) {
                    // If block.json exists, use it
                $this->registerBlock($blockName, $blockDir, $blockJsonPath);
                } else {
                    // If no block.json, register block manually
                    $this->registerBlockWithoutJson($blockName, $blockDir);
                }
            }
        }
    }

    /**
     * Register single block with block.json
     */
    private function registerBlock(string $blockName, string $blockDir, string $blockJsonPath): void
    {
        // Register block type
        register_block_type($blockJsonPath, [
            'render_callback' => function($attributes, $content, $block) use ($blockName, $blockDir) {
                $displayPath = $blockDir . 'nxw-display-' . strtolower($blockName) . '-block.php';
                
                if (file_exists($displayPath)) {
                    ob_start();
                    include $displayPath;
                    return ob_get_clean();
                }
                
                return '';
            },
        ]);

        // Blocks CSS is loaded globally in enqueueBlockAssets
    }

    /**
     * Register block without block.json file
     */
    private function registerBlockWithoutJson(string $blockName, string $blockDir): void
    {
        $blockSlug = 'nxw-page-builder/' . strtolower($blockName);
        
        register_block_type($blockSlug, [
            'render_callback' => function($attributes, $content, $block) use ($blockName, $blockDir) {
                $displayPath = $blockDir . 'nxw-display-' . strtolower($blockName) . '-block.php';
                
                if (file_exists($displayPath)) {
                    ob_start();
                    include $displayPath;
                    return ob_get_clean();
                }
                
                return '';
            },
        ]);
    }

    /**
     * Enqueue block frontend assets
     */
    public function enqueueBlockAssets(): void
    {
        if (!is_admin()) {
            // Enqueue main blocks CSS (compiled from SCSS)
            $blocksCssMinPath = $this->blocksDir . 'app.min.css';
            $blocksCssPath = $this->blocksDir . 'app.css';
            
            if (file_exists($blocksCssMinPath)) {
                wp_enqueue_style(
                    'nxw-blocks',
                    NXW_PAGE_BUILDER_PLUGIN_URL . 'src/Blocks/app.min.css',
                    [],
                    NXW_PAGE_BUILDER_VERSION
                );
            } elseif (file_exists($blocksCssPath)) {
                wp_enqueue_style(
                    'nxw-blocks',
                    NXW_PAGE_BUILDER_PLUGIN_URL . 'src/Blocks/app.css',
                    [],
                    NXW_PAGE_BUILDER_VERSION
                );
            }

            // Enqueue block-specific JS files
            $jsDir = $this->blocksDir . 'js/';
            if (is_dir($jsDir)) {
                $jsFiles = glob($jsDir . '*.js');
                
                foreach ($jsFiles as $jsFile) {
                    // Skip minified files (they'll be loaded if non-minified doesn't exist)
                    if (strpos($jsFile, '.min.js') !== false) {
                        continue;
                    }
                    
                    $blockName = basename($jsFile, '.js');
                    $jsMinPath = $jsDir . $blockName . '.min.js';
                    
                    // Check for minified version first
                    if (file_exists($jsMinPath)) {
                        wp_enqueue_script(
                            'nxw-block-' . $blockName,
                            NXW_PAGE_BUILDER_PLUGIN_URL . 'src/Blocks/js/' . $blockName . '.min.js',
                            [],
                            NXW_PAGE_BUILDER_VERSION,
                            true
                        );
                    } else {
                        wp_enqueue_script(
                            'nxw-block-' . $blockName,
                            NXW_PAGE_BUILDER_PLUGIN_URL . 'src/Blocks/js/' . $blockName . '.js',
                            [],
                            NXW_PAGE_BUILDER_VERSION,
                            true
                        );
                    }
                }
            }
        }
    }
}

