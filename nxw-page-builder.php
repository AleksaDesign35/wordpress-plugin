<?php
declare(strict_types=1);

/**
 * Plugin Name: NXW Page Builder
 * Plugin URI: https://example.com/nxw-page-builder
 * Description: A modular, scalable WordPress page builder with React blocks
 * Version: 1.0.0
 * Author: NXW
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: nxw-page-builder
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('NXW_PAGE_BUILDER_VERSION', '1.0.0');
define('NXW_PAGE_BUILDER_PLUGIN_FILE', __FILE__);
define('NXW_PAGE_BUILDER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('NXW_PAGE_BUILDER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('NXW_PAGE_BUILDER_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Autoloader - Try Composer first, fallback to custom autoloader
$autoloader = NXW_PAGE_BUILDER_PLUGIN_DIR . 'vendor/autoload.php';
if (file_exists($autoloader)) {
    require_once $autoloader;
} else {
    // Custom PSR-4 autoloader
    spl_autoload_register(function ($class) {
        $prefix = 'NXW\\PageBuilder\\';
        $baseDir = NXW_PAGE_BUILDER_PLUGIN_DIR . 'src/';
        
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }
        
        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        
        if (file_exists($file)) {
            require $file;
        }
    });
}

// Initialize plugin
function nxw_page_builder_init(): void
{
    // Ensure WordPress functions are loaded
    if (!function_exists('add_action')) {
        return;
    }
    
    try {
        $plugin = new NXW\PageBuilder\Plugin();
        $plugin->init();
    } catch (Throwable $e) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('NXW Page Builder Error: ' . $e->getMessage());
        }
    }
}

// Hook into WordPress
add_action('plugins_loaded', 'nxw_page_builder_init');

