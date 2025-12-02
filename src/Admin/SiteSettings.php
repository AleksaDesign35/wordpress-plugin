<?php
declare(strict_types=1);

namespace NXW\PageBuilder\Admin;

/**
 * Site Settings Manager
 */
class SiteSettings
{
    /**
     * Settings option key
     */
    private const OPTION_KEY = 'nxw_page_builder_site_settings';

    /**
     * Default settings (stored in rem for best practices)
     */
    private const DEFAULT_SETTINGS = [
        'site_width' => '75rem', // 1200px / 16 = 75rem
        'padding_left' => '1rem', // 16px / 16 = 1rem
        'padding_right' => '1rem', // 16px / 16 = 1rem
    ];

    /**
     * Initialize site settings
     */
    public function init(): void
    {
        add_action('admin_menu', [$this, 'addSettingsPage']);
        add_action('wp_head', [$this, 'outputInlineCSS'], 999);
        add_action('template_redirect', [$this, 'bypassThemeForPageBuilder'], 1);
    }

    /**
     * Add settings page to admin menu
     */
    public function addSettingsPage(): void
    {
        // Remove any existing menu items with same slug to avoid duplicates
        remove_submenu_page('nxw-page-builder', 'nxw-page-builder-settings');
        
        // Use the same callback as other admin pages to avoid duplicate rendering
        // This ensures PageDispatcher is only initialized once
        $adminPage = new \NXW\PageBuilder\Admin\AdminPage();
        add_submenu_page(
            'nxw-page-builder',
            __('Site Settings', 'nxw-page-builder'),
            __('Site Settings', 'nxw-page-builder'),
            'manage_options',
            'nxw-page-builder-settings',
            [$adminPage, 'renderAdminPage']
        );
    }

    /**
     * Convert px to rem (assuming 16px = 1rem)
     * 
     * @param string $value Value with unit (e.g., "1200px" or "75rem")
     * @return string Value in rem format
     */
    private function convertToRem(string $value): string
    {
        $value = trim($value);
        
        // If already in rem, return as is
        if (preg_match('/^[\d.]+rem$/i', $value)) {
            return $value;
        }
        
        // If in px, convert to rem (16px = 1rem)
        if (preg_match('/^([\d.]+)px$/i', $value, $matches)) {
            $pxValue = (float) $matches[1];
            $remValue = $pxValue / 16;
            return number_format($remValue, 3, '.', '') . 'rem';
        }
        
        // If just a number, assume px and convert
        if (preg_match('/^[\d.]+$/', $value)) {
            $pxValue = (float) $value;
            $remValue = $pxValue / 16;
            return number_format($remValue, 3, '.', '') . 'rem';
        }
        
        // If invalid format, return default rem value
        return '1rem';
    }

    /**
     * Save settings (public for use by PageDispatcher)
     */
    public function saveSettings(): void
    {
        // Get values and convert px to rem
        $siteWidth = isset($_POST['site_width']) 
            ? sanitize_text_field($_POST['site_width']) 
            : self::DEFAULT_SETTINGS['site_width'];
        $siteWidth = $this->convertToRem($siteWidth);

        $paddingLeft = isset($_POST['padding_left']) 
            ? sanitize_text_field($_POST['padding_left']) 
            : self::DEFAULT_SETTINGS['padding_left'];
        $paddingLeft = $this->convertToRem($paddingLeft);

        $paddingRight = isset($_POST['padding_right']) 
            ? sanitize_text_field($_POST['padding_right']) 
            : self::DEFAULT_SETTINGS['padding_right'];
        $paddingRight = $this->convertToRem($paddingRight);

        $settings = [
            'site_width' => $siteWidth,
            'padding_left' => $paddingLeft,
            'padding_right' => $paddingRight,
        ];

        update_option(self::OPTION_KEY, $settings);
    }

    /**
     * Get settings
     */
    public function getSettings(): array
    {
        $settings = get_option(self::OPTION_KEY, self::DEFAULT_SETTINGS);
        
        // Merge with defaults to ensure all keys exist
        $settings = array_merge(self::DEFAULT_SETTINGS, $settings);
        
        // Migrate old px values to rem format
        $needsUpdate = false;
        if (isset($settings['site_width']) && preg_match('/px$/i', $settings['site_width'])) {
            $settings['site_width'] = $this->convertToRem($settings['site_width']);
            $needsUpdate = true;
        }
        if (isset($settings['padding_left']) && preg_match('/px$/i', $settings['padding_left'])) {
            $settings['padding_left'] = $this->convertToRem($settings['padding_left']);
            $needsUpdate = true;
        }
        if (isset($settings['padding_right']) && preg_match('/px$/i', $settings['padding_right'])) {
            $settings['padding_right'] = $this->convertToRem($settings['padding_right']);
            $needsUpdate = true;
        }
        
        // Migrate old 1900px to 75rem (1200px) if exists
        if (isset($settings['site_width']) && ($settings['site_width'] === '1900px' || $settings['site_width'] === '1900')) {
            $settings['site_width'] = '75rem';
            $needsUpdate = true;
        }
        
        if ($needsUpdate) {
            update_option(self::OPTION_KEY, $settings);
        }
        
        return $settings;
    }

    /**
     * Get single setting
     */
    public function getSetting(string $key, string $default = ''): string
    {
        $settings = $this->getSettings();
        return $settings[$key] ?? $default;
    }

    /**
     * Get CSS custom properties as string
     * 
     * @return string CSS custom properties
     */
    private function getCSSCustomProperties(): string
    {
        $settings = $this->getSettings();
        
        $css = ':root {' . "\n";
        $css .= '  --site-width: ' . esc_html($settings['site_width']) . ';' . "\n";
        $css .= '  --nxw-padding-left: ' . esc_html($settings['padding_left']) . ';' . "\n";
        $css .= '  --nxw-padding-right: ' . esc_html($settings['padding_right']) . ';' . "\n";
        $css .= '}' . "\n";
        
        return $css;
    }

    /**
     * Output inline CSS with site settings as CSS custom properties
     */
    public function outputInlineCSS(): void
    {
        // Only output on pages using page builder
        if (!is_singular('page')) {
            return;
        }

        global $post;
        
        $isPageBuilderActive = get_post_meta($post->ID, '_nxw_page_builder_active', true);
        
        if (!$isPageBuilderActive || $isPageBuilderActive !== '1') {
            return;
        }

        echo '<style id="nxw-site-settings">' . "\n";
        echo $this->getCSSCustomProperties();
        echo '</style>' . "\n";
    }

    /**
     * Bypass theme for page builder pages - render blank page
     */
    public function bypassThemeForPageBuilder(): void
    {
        // Only on singular pages
        if (!is_singular('page')) {
            return;
        }

        global $post;
        
        // Check if page is using page builder
        $isPageBuilderActive = get_post_meta($post->ID, '_nxw_page_builder_active', true);
        
        if (!$isPageBuilderActive || $isPageBuilderActive !== '1') {
            return;
        }

        // Get settings
        $settings = $this->getSettings();
        
        // Get blocks
        $blocks = get_post_meta($post->ID, '_nxw_page_blocks', true);
        if (!is_array($blocks) || empty($blocks)) {
            $blocks = [];
        }

        // Render blank page with only blocks
        $this->renderBlankPage($blocks, $settings);
        exit;
    }

    /**
     * Render blank page with blocks
     */
    private function renderBlankPage(array $blocks, array $settings): void
    {
        global $post;

        // Check if user wants theme header/footer
        $useThemeHeader = get_post_meta($post->ID, '_nxw_use_theme_header', true) === '1';
        $useThemeFooter = get_post_meta($post->ID, '_nxw_use_theme_footer', true) === '1';

        // If theme header/footer not requested, render completely blank page
        if (!$useThemeHeader && !$useThemeFooter) {
            // Render blocks
            $renderer = new \NXW\PageBuilder\Frontend\Renderer();
            $renderer->init(); // Initialize renderer (sets up BlocksManager)
            
            $blocksHtml = '';
            foreach ($blocks as $block) {
                $blocksHtml .= $renderer->renderBlock($block);
            }
            
            $this->renderCompletelyBlankPage($blocksHtml, $settings, $post);
        } else {
            // Let theme handle it - the_content filter will handle blocks rendering
            // Don't exit, let WordPress continue with theme
            return;
        }
    }

    /**
     * Render completely blank page (no theme at all)
     */
    private function renderCompletelyBlankPage(string $blocksHtml, array $settings, $post): void
    {
        // Enqueue blocks CSS and scripts manually for blank page
        $blocksDir = NXW_PAGE_BUILDER_PLUGIN_DIR . 'src/Blocks/';
        
        // Enqueue blocks CSS
        $blocksCssMinPath = $blocksDir . 'app.min.css';
        $blocksCssPath = $blocksDir . 'app.css';
        $blocksCssUrl = '';
        if (file_exists($blocksCssMinPath)) {
            $blocksCssUrl = NXW_PAGE_BUILDER_PLUGIN_URL . 'src/Blocks/app.min.css';
        } elseif (file_exists($blocksCssPath)) {
            $blocksCssUrl = NXW_PAGE_BUILDER_PLUGIN_URL . 'src/Blocks/app.css';
        }

        // Enqueue block-specific JS files
        $jsDir = $blocksDir . 'js/';
        $jsFiles = [];
        if (is_dir($jsDir)) {
            $jsFilesList = glob($jsDir . '*.js');
            foreach ($jsFilesList as $jsFile) {
                if (strpos($jsFile, '.min.js') !== false) {
                    continue;
                }
                $blockName = basename($jsFile, '.js');
                $jsMinPath = $jsDir . $blockName . '.min.js';
                if (file_exists($jsMinPath)) {
                    $jsFiles[] = NXW_PAGE_BUILDER_PLUGIN_URL . 'src/Blocks/js/' . $blockName . '.min.js';
                } else {
                    $jsFiles[] = NXW_PAGE_BUILDER_PLUGIN_URL . 'src/Blocks/js/' . $blockName . '.js';
                }
            }
        }
        
        ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($post->post_title ?: get_bloginfo('name')); ?></title>
    <style id="nxw-site-settings">
        <?php echo $this->getCSSCustomProperties(); ?>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
    </style>
    <?php if ($blocksCssUrl): ?>
    <link rel="stylesheet" href="<?php echo esc_url($blocksCssUrl); ?>" />
    <?php endif; ?>
</head>
<body <?php body_class('nxw-page-builder-blank'); ?>>
    <?php echo $blocksHtml; ?>
    <?php foreach ($jsFiles as $jsUrl): ?>
    <script src="<?php echo esc_url($jsUrl); ?>"></script>
    <?php endforeach; ?>
</body>
</html>
        <?php
        exit;
    }
}

