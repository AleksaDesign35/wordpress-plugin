<?php
declare(strict_types=1);

namespace NXW\PageBuilder\Admin;

use NXW\PageBuilder\Helpers\Sanitizer;

/**
 * Pages Manager - Handles CRUD operations for pages
 */
class PagesManager
{
    /**
     * Initialize pages manager
     */
    public function init(): void
    {
        // AJAX handlers will be added here if needed
    }

    /**
     * Get all pages
     *
     * @return array
     */
    public function getAllPages(): array
    {
        global $wpdb;

        $results = $wpdb->get_results(
            "SELECT post_id, meta_value 
             FROM {$wpdb->postmeta} 
             WHERE meta_key = '_nxw_page_builder_active' 
             AND meta_value = '1'
             ORDER BY post_id DESC",
            ARRAY_A
        );

        $pages = [];
        foreach ($results as $result) {
            $post = get_post((int) $result['post_id']);
            if ($post) {
                $blocks = get_post_meta((int) $result['post_id'], '_nxw_page_blocks', true);
                $pages[] = [
                    'id' => $post->ID,
                    'title' => $post->post_title,
                    'slug' => $post->post_name,
                    'blocks' => is_array($blocks) ? $blocks : [],
                    'edit_url' => admin_url('admin.php?page=nxw-page-builder-pages&action=edit-blocks&page_id=' . $post->ID),
                ];
            }
        }

        return $pages;
    }

    /**
     * Get single page data
     *
     * @param int $pageId
     * @return array|null
     */
    public function getPage(int $pageId): ?array
    {
        $post = get_post($pageId);
        if (!$post) {
            return null;
        }

        $blocks = get_post_meta($pageId, '_nxw_page_blocks', true);
        
        return [
            'id' => $post->ID,
            'title' => $post->post_title,
            'slug' => $post->post_name,
            'wordpress_page_id' => $pageId,
            'blocks' => is_array($blocks) ? $blocks : [],
        ];
    }

    /**
     * Save page configuration
     */
    public function savePage(): void
    {
        $pageId = isset($_POST['page_id']) ? (int) $_POST['page_id'] : 0;
        $wordpressPageId = isset($_POST['wordpress_page_id']) 
            ? sanitize_text_field($_POST['wordpress_page_id']) 
            : '';

        // Create new page if needed
        if ($wordpressPageId === 'new') {
            $newPageTitle = isset($_POST['new_page_title']) 
                ? sanitize_text_field($_POST['new_page_title']) 
                : __('New Page', 'nxw-page-builder');
            
            if (empty($newPageTitle)) {
                wp_die(__('Please enter a page title.', 'nxw-page-builder'));
            }

            // Create new WordPress page
            $newPageId = wp_insert_post([
                'post_title' => $newPageTitle,
                'post_status' => 'publish',
                'post_type' => 'page',
            ]);

            if (is_wp_error($newPageId) || $newPageId === 0) {
                wp_die(__('Failed to create page.', 'nxw-page-builder'));
            }

            $wordpressPageId = (int) $newPageId;
            
            // Update POST data for redirect
            $_POST['wordpress_page_id'] = $wordpressPageId;
            $_POST['page_id'] = $wordpressPageId;
        } else {
            $wordpressPageId = (int) $wordpressPageId;
        }

        if ($wordpressPageId <= 0) {
            wp_die(__('Invalid page selected.', 'nxw-page-builder'));
        }

        // Process blocks - handle JSON encoded attributes
        $blocks = [];
        if (isset($_POST['blocks']) && is_array($_POST['blocks'])) {
            foreach ($_POST['blocks'] as $block) {
                if (!isset($block['name'])) {
                    continue;
                }
                
                $blockData = [
                    'name' => sanitize_text_field($block['name']),
                ];
                
                // Decode attributes if it's a JSON string
                if (isset($block['attributes'])) {
                    if (is_string($block['attributes'])) {
                        $decoded = json_decode($block['attributes'], true);
                        $blockData['attributes'] = is_array($decoded) ? $decoded : [];
                    } elseif (is_array($block['attributes'])) {
                        $blockData['attributes'] = Sanitizer::sanitizeArray($block['attributes']);
                    } else {
                        $blockData['attributes'] = [];
                    }
                } else {
                    $blockData['attributes'] = [];
                }
                
                $blocks[] = $blockData;
            }
        }

        // Save blocks meta
        update_post_meta($wordpressPageId, '_nxw_page_blocks', $blocks);
        
        // Mark page as active for page builder
        update_post_meta($wordpressPageId, '_nxw_page_builder_active', '1');

        // Save theme header/footer options
        $useThemeHeader = isset($_POST['use_theme_header']) && $_POST['use_theme_header'] === '1' ? '1' : '0';
        $useThemeFooter = isset($_POST['use_theme_footer']) && $_POST['use_theme_footer'] === '1' ? '1' : '0';
        update_post_meta($wordpressPageId, '_nxw_use_theme_header', $useThemeHeader);
        update_post_meta($wordpressPageId, '_nxw_use_theme_footer', $useThemeFooter);

        // Set success message in transient (will be displayed after redirect)
        set_transient('nxw_page_builder_notice_' . get_current_user_id(), [
            'type' => 'success',
            'message' => __('Page saved successfully.', 'nxw-page-builder')
        ], 30);
    }

    /**
     * Delete page configuration
     */
    public function deletePage(): void
    {
        $pageId = isset($_POST['page_id']) ? (int) $_POST['page_id'] : 0;

        if ($pageId <= 0) {
            return;
        }

        // Remove page builder meta
        delete_post_meta($pageId, '_nxw_page_blocks');
        delete_post_meta($pageId, '_nxw_page_builder_active');

        // Set success message in transient
        set_transient('nxw_page_builder_notice_' . get_current_user_id(), [
            'type' => 'success',
            'message' => __('Page configuration deleted successfully.', 'nxw-page-builder')
        ], 30);
    }
}

