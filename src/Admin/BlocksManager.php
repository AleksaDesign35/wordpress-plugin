<?php
declare(strict_types=1);

namespace NXW\PageBuilder\Admin;

/**
 * Blocks Manager - Handles block discovery and management
 */
class BlocksManager
{
    /**
     * Blocks directory
     */
    private string $blocksDir;

    /**
     * Initialize blocks manager
     */
    public function init(): void
    {
        $this->blocksDir = NXW_PAGE_BUILDER_PLUGIN_DIR . 'src/Blocks/';
        
        // Register AJAX handler for block preview
        add_action('wp_ajax_nxw_get_block_preview', [$this, 'ajaxGetBlockPreview']);
    }

    /**
     * Get all available blocks
     *
     * @return array
     */
    public function getAllBlocks(): array
    {
        $blocks = [];
        $blockDirs = glob($this->blocksDir . '*/', GLOB_ONLYDIR);

        foreach ($blockDirs as $blockDir) {
            $blockName = basename($blockDir);
            $blockJsonPath = $blockDir . 'block.json';

            if (file_exists($blockJsonPath)) {
                $blockData = json_decode(file_get_contents($blockJsonPath), true);
                if ($blockData) {
                    $blocks[] = [
                        'name' => $blockName,
                        'title' => $blockData['title'] ?? ucfirst($blockName),
                        'description' => $blockData['description'] ?? '',
                        'category' => $blockData['category'] ?? 'common',
                        'icon' => $blockData['icon'] ?? 'block-default',
                        'keywords' => $blockData['keywords'] ?? [],
                        'supports' => $blockData['supports'] ?? [],
                        'attributes' => $blockData['attributes'] ?? [],
                        'path' => $blockDir,
                    ];
                }
            }
        }

        return $blocks;
    }

    /**
     * Get single block data
     *
     * @param string $blockName
     * @return array|null
     */
    public function getBlock(string $blockName): ?array
    {
        $blockDir = $this->blocksDir . $blockName . '/';
        $blockJsonPath = $blockDir . 'block.json';

        if (!file_exists($blockJsonPath)) {
            return null;
        }

        $blockData = json_decode(file_get_contents($blockJsonPath), true);
        if (!$blockData) {
            return null;
        }

        return [
            'name' => $blockName,
            'title' => $blockData['title'] ?? ucfirst($blockName),
            'description' => $blockData['description'] ?? '',
            'category' => $blockData['category'] ?? 'common',
            'icon' => $blockData['icon'] ?? 'block-default',
            'keywords' => $blockData['keywords'] ?? [],
            'supports' => $blockData['supports'] ?? [],
            'attributes' => $blockData['attributes'] ?? [],
            'path' => $blockDir,
        ];
    }

    /**
     * Get all unique categories from blocks
     *
     * @return array
     */
    public function getCategories(): array
    {
        $blocks = $this->getAllBlocks();
        $categories = [];

        foreach ($blocks as $block) {
            $category = $block['category'] ?? 'common';
            if (!in_array($category, $categories, true)) {
                $categories[] = $category;
            }
        }

        sort($categories);
        return $categories;
    }

    /**
     * Render block preview with default attributes
     *
     * @param string $blockName
     * @return string
     */
    public function renderBlockPreview(string $blockName): string
    {
        $blockData = $this->getBlock($blockName);
        
        if (!$blockData) {
            return '';
        }

        $viewPath = $blockData['path'] . 'view.php';
        
        if (!file_exists($viewPath)) {
            return '';
        }

        // Get default attributes from block.json
        $defaultAttributes = [];
        if (isset($blockData['attributes'])) {
            foreach ($blockData['attributes'] as $key => $attr) {
                if (isset($attr['default'])) {
                    $defaultAttributes[$key] = $attr['default'];
                }
            }
        }

        // Render block with default attributes
        ob_start();
        $attributes = $defaultAttributes;
        include $viewPath;
        return ob_get_clean();
    }

    /**
     * AJAX handler to get block preview
     */
    public function ajaxGetBlockPreview(): void
    {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'nxw_block_preview_nonce')) {
            wp_send_json_error(['message' => __('Security check failed', 'nxw-page-builder')]);
            return;
        }

        // Check user permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Insufficient permissions', 'nxw-page-builder')]);
            return;
        }

        $blockName = isset($_POST['block_name']) ? sanitize_text_field($_POST['block_name']) : '';
        
        if (empty($blockName)) {
            wp_send_json_error(['message' => __('Block name is required', 'nxw-page-builder')]);
            return;
        }

        $blockData = $this->getBlock($blockName);
        
        if (!$blockData) {
            wp_send_json_error(['message' => __('Block not found', 'nxw-page-builder')]);
            return;
        }

        $preview = $this->renderBlockPreview($blockName);
        
        // Format attributes for display
        $formattedAttributes = [];
        if (isset($blockData['attributes']) && is_array($blockData['attributes'])) {
            foreach ($blockData['attributes'] as $key => $attr) {
                $formattedAttributes[] = [
                    'name' => $key,
                    'type' => $attr['type'] ?? 'string',
                    'default' => isset($attr['default']) ? $attr['default'] : null,
                    'description' => $attr['description'] ?? '',
                ];
            }
        }

        wp_send_json_success([
            'preview' => $preview,
            'attributes' => $formattedAttributes,
            'title' => $blockData['title'] ?? ucfirst($blockName),
            'description' => $blockData['description'] ?? '',
        ]);
    }
}

