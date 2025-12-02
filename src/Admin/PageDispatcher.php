<?php
declare(strict_types=1);

namespace NXW\PageBuilder\Admin;

use NXW\PageBuilder\Admin\PagesManager;
use NXW\PageBuilder\Admin\BlocksManager;
use NXW\PageBuilder\Admin\SiteSettings;
use NXW\PageBuilder\Helpers\TemplateHelper;

/**
 * Page Dispatcher - Routes admin requests
 */
class PageDispatcher
{
    /**
     * Pages manager instance
     */
    private ?PagesManager $pagesManager = null;

    /**
     * Blocks manager instance
     */
    private ?BlocksManager $blocksManager = null;

    /**
     * Site settings instance
     */
    private ?SiteSettings $siteSettings = null;

    /**
     * Whether init has been called
     */
    private bool $initialized = false;

    /**
     * Initialize dispatcher
     */
    public function init(): void
    {
        // Prevent double initialization
        if ($this->initialized) {
            return;
        }

        $this->pagesManager = new PagesManager();
        $this->pagesManager->init();
        
        $this->blocksManager = new BlocksManager();
        $this->blocksManager->init();
        
        $this->siteSettings = new SiteSettings();
        $this->siteSettings->init();
        
        $this->initialized = true;
    }

    /**
     * Get pages manager instance
     */
    private function getPagesManager(): PagesManager
    {
        if ($this->pagesManager === null) {
            $this->pagesManager = new PagesManager();
            $this->pagesManager->init();
        }
        return $this->pagesManager;
    }

    /**
     * Get blocks manager instance
     */
    private function getBlocksManager(): BlocksManager
    {
        if ($this->blocksManager === null) {
            $this->blocksManager = new BlocksManager();
            $this->blocksManager->init();
        }
        return $this->blocksManager;
    }

    /**
     * Get site settings instance
     */
    private function getSiteSettings(): SiteSettings
    {
        if ($this->siteSettings === null) {
            $this->siteSettings = new SiteSettings();
            $this->siteSettings->init();
        }
        return $this->siteSettings;
    }

    /**
     * Dispatch admin page request
     */
    public function dispatch(): void
    {
        // Get current page from GET or POST (POST takes priority for form submissions)
        $currentPage = isset($_POST['page']) ? sanitize_text_field($_POST['page']) : ($_GET['page'] ?? 'nxw-page-builder');
        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
        $pageId = isset($_GET['page_id']) ? (int) $_GET['page_id'] : 0;

        // Handle form submissions
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nxw_action'])) {
            // If page is not in POST, try to get it from GET or URL
            if (empty($currentPage) || $currentPage === 'nxw-page-builder') {
                $currentPage = $_GET['page'] ?? 'nxw-page-builder-settings';
            }
            $this->handlePostRequest($currentPage);
            return;
        }

        // Route based on current page
        switch ($currentPage) {
            case 'nxw-page-builder-pages':
                $this->dispatchPages($action, $pageId);
                break;
            case 'nxw-page-builder-blocks':
                $this->dispatchBlocks();
                break;
            case 'nxw-page-builder-settings':
                $this->renderSiteSettings();
                break;
            case 'nxw-page-builder':
            default:
                $this->renderDashboard();
                break;
        }
    }

    /**
     * Dispatch Pages routes
     */
    private function dispatchPages(string $action, int $pageId): void
    {
        switch ($action) {
            case 'add':
                $this->renderPageForm(0);
                break;
            case 'edit':
                $this->renderPageForm($pageId);
                break;
            case 'edit-blocks':
                $this->renderPageBlocksEditor($pageId);
                break;
            case 'list':
            default:
                $this->renderPagesList();
                break;
        }
    }

    /**
     * Dispatch Blocks routes
     */
    private function dispatchBlocks(): void
    {
        $this->renderBlocksList();
    }

    /**
     * Handle POST requests
     */
    private function handlePostRequest(string $currentPage): void
    {
        // Check nonce - can be either nxw-page-builder-nonce or nxw_site_settings_nonce
        $nonceChecked = false;
        if (isset($_POST['nxw_nonce']) && wp_verify_nonce($_POST['nxw_nonce'], 'nxw-page-builder-nonce')) {
            $nonceChecked = true;
        } elseif (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'nxw_site_settings_nonce')) {
            $nonceChecked = true;
        }
        
        if (!$nonceChecked) {
            wp_die(__('Security check failed', 'nxw-page-builder'));
        }

        $action = sanitize_text_field($_POST['nxw_action'] ?? '');
        $redirectUrl = admin_url('admin.php?page=' . $currentPage);

        switch ($action) {
            case 'save_page':
                $this->getPagesManager()->savePage();
                $redirectUrl = admin_url('admin.php?page=nxw-page-builder-pages');
                break;
            case 'save_page_blocks':
                $this->getPagesManager()->savePage();
                $pageId = isset($_POST['page_id']) ? (int) $_POST['page_id'] : 0;
                if ($pageId > 0) {
                    $redirectUrl = admin_url('admin.php?page=nxw-page-builder-pages&action=edit-blocks&page_id=' . $pageId);
                } else {
                    $redirectUrl = admin_url('admin.php?page=nxw-page-builder-pages');
                }
                break;
            case 'save_site_settings':
                $this->getSiteSettings()->saveSettings();
                $tab = isset($_POST['tab']) ? sanitize_text_field($_POST['tab']) : 'layout';
                set_transient('nxw_page_builder_notice_' . get_current_user_id(), [
                    'type' => 'success',
                    'message' => __('Site settings saved successfully.', 'nxw-page-builder')
                ], 5);
                $redirectUrl = admin_url('admin.php?page=nxw-page-builder-settings&tab=' . esc_attr($tab));
                break;
            case 'delete_page':
                $this->getPagesManager()->deletePage();
                $redirectUrl = admin_url('admin.php?page=nxw-page-builder-pages');
                break;
        }

        // Redirect after processing (must be before any output)
        if (!headers_sent()) {
            wp_safe_redirect($redirectUrl);
            exit;
        } else {
            // Fallback if headers already sent
            echo '<script>window.location.href = "' . esc_js($redirectUrl) . '";</script>';
            exit;
        }
    }

    /**
     * Render dashboard
     */
    private function renderDashboard(): void
    {
        $pagesManager = $this->getPagesManager();
        $blocksManager = $this->getBlocksManager();
        
        $totalPages = count($pagesManager->getAllPages());
        $totalBlocks = count($blocksManager->getAllBlocks());
        $recentPages = array_slice($pagesManager->getAllPages(), 0, 5);
        
        TemplateHelper::render('admin/layout', [
            'title' => __('Dashboard', 'nxw-page-builder'),
            'content' => TemplateHelper::render('admin/dashboard', [
                'totalPages' => $totalPages,
                'totalBlocks' => $totalBlocks,
                'recentPages' => $recentPages,
            ], true),
        ]);
    }

    /**
     * Render pages list
     */
    private function renderPagesList(): void
    {
        $pages = $this->getPagesManager()->getAllPages();
        
        TemplateHelper::render('admin/layout', [
            'title' => __('Pages', 'nxw-page-builder'),
            'content' => TemplateHelper::render('admin/pages-list', [
                'pages' => $pages,
            ], true),
        ]);
    }

    /**
     * Render page form (add/edit)
     */
    private function renderPageForm(int $pageId): void
    {
        $page = null;
        $wordpressPages = get_pages([
            'sort_column' => 'post_title',
            'sort_order' => 'ASC',
        ]);

        if ($pageId > 0) {
            $page = $this->getPagesManager()->getPage($pageId);
        }

        TemplateHelper::render('admin/layout', [
            'title' => $pageId > 0 
                ? __('Edit Page', 'nxw-page-builder') 
                : __('Add New Page', 'nxw-page-builder'),
            'content' => TemplateHelper::render('admin/page-form', [
                'page' => $page,
                'pageId' => $pageId,
                'wordpressPages' => $wordpressPages,
            ], true),
        ]);
    }

    /**
     * Render blocks list
     */
    private function renderBlocksList(): void
    {
        $blocksManager = $this->getBlocksManager();
        $blocks = $blocksManager->getAllBlocks();
        $categories = $blocksManager->getCategories();
        
        TemplateHelper::render('admin/layout', [
            'title' => __('All Blocks', 'nxw-page-builder'),
            'content' => TemplateHelper::render('admin/blocks-list', [
                'blocks' => $blocks,
                'categories' => $categories,
                'blocksManager' => $blocksManager,
            ], true),
        ]);
    }

    /**
     * Render page blocks editor
     */
    private function renderPageBlocksEditor(int $pageId): void
    {
        if ($pageId <= 0) {
            wp_die(__('Invalid page ID.', 'nxw-page-builder'));
        }

        $page = $this->getPagesManager()->getPage($pageId);
        if (!$page) {
            wp_die(__('Page not found.', 'nxw-page-builder'));
        }

        $availableBlocks = $this->getBlocksManager()->getAllBlocks();
        
        TemplateHelper::render('admin/layout', [
            'title' => __('Edit Blocks', 'nxw-page-builder') . ' - ' . $page['title'],
            'content' => TemplateHelper::render('admin/page-blocks-editor', [
                'page' => $page,
                'pageId' => $pageId,
                'availableBlocks' => $availableBlocks,
            ], true),
        ]);
    }

    /**
     * Render site settings page
     */
    private function renderSiteSettings(): void
    {
        $settings = $this->getSiteSettings()->getSettings();
        
        // Get active tab from query string or default to 'layout'
        $activeTab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'layout';
        
        // Render with layout, passing settings and activeTab to the view
        TemplateHelper::render('admin/layout', [
            'title' => __('Site Settings', 'nxw-page-builder'),
            'subtitle' => __('Configure global settings that apply to all pages using the page builder.', 'nxw-page-builder'),
            'content' => TemplateHelper::render('admin/site-settings', [
                'settings' => $settings,
                'activeTab' => $activeTab,
            ], true),
        ]);
    }
}

