<?php
declare(strict_types=1);

namespace NXW\PageBuilder\Frontend;

use NXW\PageBuilder\Admin\BlocksManager;

/**
 * Frontend Renderer - Renders blocks on frontend pages
 */
class Renderer
{
    /**
     * Blocks manager instance
     */
    private BlocksManager $blocksManager;

    /**
     * Initialize renderer
     */
    public function init(): void
    {
        $this->blocksManager = new BlocksManager();
        $this->blocksManager->init();

        // Hook into the_content to render blocks (high priority to run last)
        add_filter('the_content', [$this, 'renderPageBlocks'], 999);
        
        // Enqueue frontend assets
        add_action('wp_enqueue_scripts', [$this, 'enqueueFrontendAssets']);
    }

    /**
     * Render blocks for a page
     */
    public function renderPageBlocks(string $content): string
    {
        // Only render on singular pages
        if (!is_singular('page')) {
            return $content;
        }

        global $post;
        
        // Check if page is using page builder
        $isPageBuilderActive = get_post_meta($post->ID, '_nxw_page_builder_active', true);
        
        if (!$isPageBuilderActive || $isPageBuilderActive !== '1') {
            return $content;
        }

        // Get blocks for this page
        $blocks = get_post_meta($post->ID, '_nxw_page_blocks', true);
        
        if (!is_array($blocks) || empty($blocks)) {
            // Return empty string to make page blank if no blocks
            return '';
        }

        // Render blocks directly - no wrapper, blocks render as-is
        $blocksHtml = '';
        foreach ($blocks as $block) {
            $blocksHtml .= $this->renderBlock($block);
        }

        // Replace content with blocks (page is blank)
        return $blocksHtml;
    }

    /**
     * Render single block
     * 
     * @param array $block Block data
     * @return string Rendered block HTML
     */
    public function renderBlock(array $block): string
    {
        $blockName = $block['name'] ?? '';
        $blockAttributes = $block['attributes'] ?? [];

        if (empty($blockName)) {
            return '';
        }

        // Get block data
        $blockData = $this->blocksManager->getBlock($blockName);
        
        if (!$blockData) {
            return '';
        }

        // Get block display template path
        $blockPath = $blockData['path'];
        $blockName = $blockData['name'];
        $displayPath = $blockPath . 'nxw-display-' . strtolower($blockName) . '-block.php';

        if (!file_exists($displayPath)) {
            return '';
        }

        // Render block view
        ob_start();
        $attributes = $blockAttributes; // Make attributes available to view template
        include $displayPath;
        return ob_get_clean();
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueueFrontendAssets(): void
    {
        // Only on pages using page builder
        if (!is_singular('page')) {
            return;
        }

        global $post;
        
        $isPageBuilderActive = get_post_meta($post->ID, '_nxw_page_builder_active', true);
        
        if (!$isPageBuilderActive || $isPageBuilderActive !== '1') {
            return;
        }

        // Main blocks CSS is enqueued by BlockLoader
        // This method is kept for any additional frontend-specific assets if needed
    }
}

