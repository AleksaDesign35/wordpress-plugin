<?php
declare(strict_types=1);

namespace NXW\PageBuilder\Admin;

/**
 * Admin Page Handler
 */
class AdminPage
{
    /**
     * Initialize admin page
     */
    public function init(): void
    {
        add_action('admin_menu', [$this, 'addAdminMenu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminAssets']);
        add_action('admin_notices', [$this, 'displayAdminNotices']);
    }

    /**
     * Display admin notices from transients
     */
    public function displayAdminNotices(): void
    {
        $notice = get_transient('nxw_page_builder_notice_' . get_current_user_id());
        
        if ($notice && isset($notice['type']) && isset($notice['message'])) {
            $class = 'notice-' . esc_attr($notice['type']);
            echo '<div class="notice ' . $class . ' is-dismissible"><p>' . 
                 esc_html($notice['message']) . 
                 '</p></div>';
            
            // Delete transient after displaying
            delete_transient('nxw_page_builder_notice_' . get_current_user_id());
        }
    }

    /**
     * Add admin menu
     */
    public function addAdminMenu(): void
    {
        // Main menu page (Dashboard)
        add_menu_page(
            __('NXW Page Builder', 'nxw-page-builder'),
            __('NXW Page Builder', 'nxw-page-builder'),
            'manage_options',
            'nxw-page-builder',
            [$this, 'renderAdminPage'],
            'dashicons-layout',
            30
        );

        // Dashboard (same as main menu, hidden from submenu)
        add_submenu_page(
            'nxw-page-builder',
            __('Dashboard', 'nxw-page-builder'),
            __('Dashboard', 'nxw-page-builder'),
            'manage_options',
            'nxw-page-builder',
            [$this, 'renderAdminPage']
        );

        // Pages submenu
        add_submenu_page(
            'nxw-page-builder',
            __('Pages', 'nxw-page-builder'),
            __('Pages', 'nxw-page-builder'),
            'manage_options',
            'nxw-page-builder-pages',
            [$this, 'renderAdminPage']
        );

        // Blocks submenu
        add_submenu_page(
            'nxw-page-builder',
            __('Blocks', 'nxw-page-builder'),
            __('Blocks', 'nxw-page-builder'),
            'manage_options',
            'nxw-page-builder-blocks',
            [$this, 'renderAdminPage']
        );

        // Site Settings is added by SiteSettings class
    }

    /**
     * Enqueue admin assets
     */
    public function enqueueAdminAssets(string $hook): void
    {
        // Only load on our admin pages
        if (strpos($hook, 'nxw-page-builder') === false) {
            return;
        }

        // Enqueue TailwindCSS Play CDN (must load in head, not footer)
        wp_enqueue_script(
            'tailwindcss-cdn',
            'https://cdn.tailwindcss.com',
            [],
            '3.4.0',
            false // Load in head, not footer
        );

        // Enqueue admin CSS (must load after Tailwind)
        // Check for minified version first, fallback to regular
        $adminCss = file_exists(NXW_PAGE_BUILDER_PLUGIN_DIR . 'assets/css/admin.min.css')
            ? 'assets/css/admin.min.css'
            : 'assets/css/admin.css';
        
        wp_enqueue_style(
            'nxw-page-builder-admin',
            NXW_PAGE_BUILDER_PLUGIN_URL . $adminCss,
            [],
            NXW_PAGE_BUILDER_VERSION
        );

        // Enqueue admin JS
        // Check for minified version first, fallback to regular
        $adminJs = file_exists(NXW_PAGE_BUILDER_PLUGIN_DIR . 'assets/js/admin.min.js')
            ? 'assets/js/admin.min.js'
            : 'assets/js/admin.js';
        
        wp_enqueue_script(
            'nxw-page-builder-admin',
            NXW_PAGE_BUILDER_PLUGIN_URL . $adminJs,
            ['jquery', 'jquery-ui-sortable'],
            NXW_PAGE_BUILDER_VERSION,
            true
        );

        // Enqueue block CSS on blocks page for preview
        if (strpos($hook, 'nxw-page-builder-blocks') !== false) {
            $blocksCssMinPath = NXW_PAGE_BUILDER_PLUGIN_DIR . 'src/Blocks/app.min.css';
            $blocksCssPath = NXW_PAGE_BUILDER_PLUGIN_DIR . 'src/Blocks/app.css';
            
            if (file_exists($blocksCssMinPath)) {
                wp_enqueue_style(
                    'nxw-blocks-preview',
                    NXW_PAGE_BUILDER_PLUGIN_URL . 'src/Blocks/app.min.css',
                    [],
                    NXW_PAGE_BUILDER_VERSION
                );
            } elseif (file_exists($blocksCssPath)) {
                wp_enqueue_style(
                    'nxw-blocks-preview',
                    NXW_PAGE_BUILDER_PLUGIN_URL . 'src/Blocks/app.css',
                    [],
                    NXW_PAGE_BUILDER_VERSION
                );
            }
        }

        // Localize script
        wp_localize_script(
            'nxw-page-builder-admin',
            'nxwPageBuilder',
            [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('nxw-page-builder-nonce'),
                'strings' => [
                    'confirmDelete' => __('Are you sure you want to delete this page?', 'nxw-page-builder'),
                    'saving' => __('Saving...', 'nxw-page-builder'),
                    'error' => __('An error occurred. Please try again.', 'nxw-page-builder'),
                    'success' => __('Operation completed successfully.', 'nxw-page-builder'),
                ],
            ]
        );
    }

    /**
     * Render admin page
     */
    public function renderAdminPage(): void
    {
        // Load the page dispatcher to handle different views
        $pageDispatcher = new PageDispatcher();
        $pageDispatcher->dispatch();
    }
}

