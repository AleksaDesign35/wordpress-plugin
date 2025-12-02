<?php
declare(strict_types=1);

namespace NXW\PageBuilder;

use NXW\PageBuilder\Admin\AdminPage;
use NXW\PageBuilder\Admin\PageDispatcher;
use NXW\PageBuilder\Admin\SiteSettings;
use NXW\PageBuilder\Blocks\BlockLoader;
use NXW\PageBuilder\Frontend\Renderer;

/**
 * Main Plugin Class
 */
class Plugin
{
    /**
     * Admin page instance
     */
    private ?AdminPage $adminPage = null;

    /**
     * Page dispatcher instance
     */
    private ?PageDispatcher $pageDispatcher = null;

    /**
     * Block loader instance
     */
    private ?BlockLoader $blockLoader = null;

    /**
     * Frontend renderer instance
     */
    private ?Renderer $frontendRenderer = null;

    /**
     * Site settings instance
     */
    private ?SiteSettings $siteSettings = null;

    /**
     * Initialize the plugin
     */
    public function init(): void
    {
        // Load text domain
        load_plugin_textdomain(
            'nxw-page-builder',
            false,
            dirname(NXW_PAGE_BUILDER_PLUGIN_BASENAME) . '/languages'
        );

        // Initialize blocks (must be early)
        $this->initBlocks();

        // Initialize admin
        if (is_admin()) {
            $this->initAdmin();
        }

        // Initialize frontend
        $this->initFrontend();
    }

    /**
     * Initialize blocks
     */
    private function initBlocks(): void
    {
        $this->blockLoader = new BlockLoader();
        $this->blockLoader->init();
    }

    /**
     * Initialize admin functionality
     */
    private function initAdmin(): void
    {
        $this->adminPage = new AdminPage();
        $this->adminPage->init();

        $this->pageDispatcher = new PageDispatcher();
        $this->pageDispatcher->init();

        $this->siteSettings = new SiteSettings();
        $this->siteSettings->init();
    }

    /**
     * Initialize frontend functionality
     */
    private function initFrontend(): void
    {
        $this->frontendRenderer = new Renderer();
        $this->frontendRenderer->init();

        // Initialize site settings for frontend CSS output
        if (!$this->siteSettings) {
            $this->siteSettings = new SiteSettings();
            $this->siteSettings->init();
        }
    }

    /**
     * Get admin page instance
     */
    public function getAdminPage(): ?AdminPage
    {
        return $this->adminPage;
    }
}

