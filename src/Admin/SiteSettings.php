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
        'default_font_size' => '16', // Base font size in px
        'site_width' => '75rem', // 1200px / 16 = 75rem
        'padding_left' => '1rem', // 16px / 16 = 1rem
        'padding_right' => '1rem', // 16px / 16 = 1rem
    ];

    /**
     * Initialize site settings
     */
    public function init(): void
    {
        // Menu is added by AdminPage class to avoid duplication
        add_action('wp_head', [$this, 'outputInlineCSS'], 999);
        add_action('template_redirect', [$this, 'bypassThemeForPageBuilder'], 1);
    }

    /**
     * Get default font size in pixels
     * 
     * @return int Font size in pixels
     */
    private function getDefaultFontSize(): int
    {
        $settings = $this->getSettings();
        $fontSize = isset($settings['default_font_size']) ? (int) $settings['default_font_size'] : 16;
        return $fontSize > 0 ? $fontSize : 16;
    }

    /**
     * Convert px to rem using current default font size
     * 
     * @param string $value Value with unit (e.g., "1200px" or "75rem")
     * @param int|null $fontSize Optional font size (uses default if not provided)
     * @return string Value in rem format
     */
    private function convertToRem(string $value, ?int $fontSize = null): string
    {
        $value = trim($value);
        if ($fontSize === null) {
            $fontSize = $this->getDefaultFontSize();
        }
        
        // If already in rem, return as is
        if (preg_match('/^[\d.]+rem$/i', $value)) {
            return $value;
        }
        
        // If in px, convert to rem using current font size
        if (preg_match('/^([\d.]+)px$/i', $value, $matches)) {
            $pxValue = (float) $matches[1];
            $remValue = $pxValue / $fontSize;
            return number_format($remValue, 3, '.', '') . 'rem';
        }
        
        // If just a number, assume px and convert
        if (preg_match('/^[\d.]+$/', $value)) {
            $pxValue = (float) $value;
            $remValue = $pxValue / $fontSize;
            return number_format($remValue, 3, '.', '') . 'rem';
        }
        
        // If invalid format, return default rem value
        return '1rem';
    }

    /**
     * Convert rem to px using current default font size
     * 
     * @param string $value Value in rem format (e.g., "75rem")
     * @return string Value in px format (e.g., "1200px")
     */
    private function convertRemToPx(string $value): string
    {
        $value = trim($value);
        $fontSize = $this->getDefaultFontSize();
        
        // If in rem, convert to px using current font size
        if (preg_match('/^([\d.]+)rem$/i', $value, $matches)) {
            $remValue = (float) $matches[1];
            $pxValue = $remValue * $fontSize;
            return number_format($pxValue, 0, '.', '') . 'px';
        }
        
        // If already in px, return as is
        if (preg_match('/^[\d.]+px$/i', $value)) {
            return $value;
        }
        
        // If just a number, assume it's px
        if (preg_match('/^[\d.]+$/', $value)) {
            return $value . 'px';
        }
        
        // If invalid format, return default px value
        return $fontSize . 'px';
    }

    /**
     * Save settings (public for use by PageDispatcher)
     */
    public function saveSettings(): void
    {
        // Get default font size first (needed for conversions)
        $defaultFontSize = isset($_POST['default_font_size']) 
            ? (int) sanitize_text_field($_POST['default_font_size']) 
            : self::DEFAULT_SETTINGS['default_font_size'];
        
        // Validate font size
        if ($defaultFontSize < 10 || $defaultFontSize > 30) {
            $defaultFontSize = self::DEFAULT_SETTINGS['default_font_size'];
        }

        // Get current settings to check if font size changed
        $currentSettings = $this->getSettings();
        $fontSizeChanged = isset($currentSettings['default_font_size']) && 
                          (int)$currentSettings['default_font_size'] !== $defaultFontSize;

        // Save font size first (needed for conversions)
        $settings = [
            'default_font_size' => (string) $defaultFontSize,
        ];

        // If font size changed, we need to recalculate all rem values
        if ($fontSizeChanged) {
            // Get existing rem values and recalculate based on new font size
            $oldFontSize = isset($currentSettings['default_font_size']) 
                ? (int) $currentSettings['default_font_size'] 
                : 16;
            
            // Recalculate existing rem values to new base
            foreach (['site_width', 'padding_left', 'padding_right'] as $key) {
                if (isset($currentSettings[$key]) && preg_match('/^([\d.]+)rem$/i', $currentSettings[$key], $matches)) {
                    $oldRemValue = (float) $matches[1];
                    // Convert old rem to px using old font size
                    $pxValue = $oldRemValue * $oldFontSize;
                    // Convert px to rem using new font size
                    $newRemValue = $pxValue / $defaultFontSize;
                    $settings[$key] = number_format($newRemValue, 3, '.', '') . 'rem';
                } else {
                    $settings[$key] = $currentSettings[$key] ?? self::DEFAULT_SETTINGS[$key];
                }
            }
        } else {
            // Font size didn't change, just convert new values using current font size
            $siteWidth = isset($_POST['site_width']) 
                ? sanitize_text_field($_POST['site_width']) 
                : ($currentSettings['site_width'] ?? self::DEFAULT_SETTINGS['site_width']);
            $settings['site_width'] = $this->convertToRem($siteWidth, $defaultFontSize);

            $paddingLeft = isset($_POST['padding_left']) 
                ? sanitize_text_field($_POST['padding_left']) 
                : ($currentSettings['padding_left'] ?? self::DEFAULT_SETTINGS['padding_left']);
            $settings['padding_left'] = $this->convertToRem($paddingLeft, $defaultFontSize);

            $paddingRight = isset($_POST['padding_right']) 
                ? sanitize_text_field($_POST['padding_right']) 
                : ($currentSettings['padding_right'] ?? self::DEFAULT_SETTINGS['padding_right']);
            $settings['padding_right'] = $this->convertToRem($paddingRight, $defaultFontSize);
        }

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
     * Get settings with px values for display
     * 
     * @return array Settings array with px values added
     */
    public function getSettingsForDisplay(): array
    {
        $settings = $this->getSettings();
        
        // Add px values for display
        $settings['site_width_px'] = $this->convertRemToPx($settings['site_width']);
        $settings['padding_left_px'] = $this->convertRemToPx($settings['padding_left']);
        $settings['padding_right_px'] = $this->convertRemToPx($settings['padding_right']);
        
        // Also keep rem values for hidden fields
        $settings['site_width_rem'] = $settings['site_width'];
        $settings['padding_left_rem'] = $settings['padding_left'];
        $settings['padding_right_rem'] = $settings['padding_right'];
        
        return $settings;
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

