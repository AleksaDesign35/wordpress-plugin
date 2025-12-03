<?php
declare(strict_types=1);

/**
 * Site Settings Template
 *
 * @var array $settings
 * @var string $activeTab
 */

// Define available tabs
$tabs = [
    'layout' => [
        'label' => __('Layout', 'nxw-page-builder'),
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>',
    ],
    // Future tabs can be added here
    // 'typography' => [...],
    // 'colors' => [...],
];

$currentPage = 'nxw-page-builder-settings';
?>

<div class="flex h-full min-h-[calc(100vh-120px)]">
    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-gray-50 border-r border-gray-200 flex-shrink-0">
        <div class="p-4">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 px-3">
                <?php esc_html_e('Settings', 'nxw-page-builder'); ?>
            </h3>
            <nav class="space-y-1">
                <?php foreach ($tabs as $tabId => $tab): 
                    $isActive = $activeTab === $tabId;
                    $tabUrl = admin_url('admin.php?page=' . $currentPage . '&tab=' . $tabId);
                ?>
                    <a href="<?php echo esc_url($tabUrl); ?>" 
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 <?php echo $isActive 
                           ? 'bg-blue-50 text-blue-700 border border-blue-200 shadow-sm' 
                           : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900'; ?>">
                        <svg class="h-5 w-5 mr-3 flex-shrink-0 <?php echo $isActive ? 'text-blue-600' : 'text-gray-400'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <?php echo $tab['icon']; ?>
                        </svg>
                        <span><?php echo esc_html($tab['label']); ?></span>
                        <?php if ($isActive): ?>
                            <svg class="h-4 w-4 ml-auto text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden bg-white">
        <!-- Content -->
        <div class="flex-1 overflow-y-auto">
            <div class="p-8 max-w-4xl">
                <!-- Form -->
                <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=' . $currentPage)); ?>" class="space-y-8">
                    <?php wp_nonce_field('nxw_site_settings_nonce', '_wpnonce'); ?>
                    <input type="hidden" name="nxw_action" value="save_site_settings">
                    <input type="hidden" name="tab" value="<?php echo esc_attr($activeTab); ?>">

                    <!-- Layout Tab Content -->
                    <?php if ($activeTab === 'layout'): ?>
                        <div class="space-y-8">
                            <!-- Header -->
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                                    <?php esc_html_e('Layout Settings', 'nxw-page-builder'); ?>
                                </h2>
                                <p class="text-sm text-gray-500">
                                    <?php esc_html_e('Configure the layout and spacing settings for your page builder content.', 'nxw-page-builder'); ?>
                                </p>
                            </div>

                            <!-- Settings Cards -->
                            <div class="space-y-6">
                                <!-- Default Font Size Card -->
                                <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-100 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 bg-amber-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <label for="default_font_size" class="block text-base font-semibold text-gray-900">
                                                    <?php esc_html_e('Default Font Size', 'nxw-page-builder'); ?>
                                                </label>
                                                <p class="mt-1 text-sm text-gray-600">
                                                    <?php esc_html_e('Base font size for rem calculations (px)', 'nxw-page-builder'); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <div class="relative group">
                                            <select id="default_font_size" 
                                                    name="default_font_size" 
                                                    class="block w-full pl-4 pr-10 py-3.5 text-base text-gray-900 bg-white border-2 border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200 hover:border-gray-300 appearance-none cursor-pointer">
                                                <option value="14" <?php selected($settings['default_font_size'] ?? '16', '14'); ?>>14px</option>
                                                <option value="16" <?php selected($settings['default_font_size'] ?? '16', '16'); ?>>16px</option>
                                                <option value="18" <?php selected($settings['default_font_size'] ?? '16', '18'); ?>>18px</option>
                                                <option value="20" <?php selected($settings['default_font_size'] ?? '16', '20'); ?>>20px</option>
                                                <option value="24" <?php selected($settings['default_font_size'] ?? '16', '24'); ?>>24px</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex items-start space-x-2">
                                            <svg class="h-4 w-4 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-xs text-gray-600 leading-relaxed">
                                                <?php esc_html_e('Changing this value will automatically recalculate all rem values. For example, if you change from 16px to 18px, all existing rem values will be adjusted accordingly.', 'nxw-page-builder'); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Site Width Card -->
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <label for="site_width" class="block text-base font-semibold text-gray-900">
                                                    <?php esc_html_e('Site Width', 'nxw-page-builder'); ?>
                                                </label>
                                                <p class="mt-1 text-sm text-gray-600">
                                                    <?php esc_html_e('Maximum width for page builder content', 'nxw-page-builder'); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <div class="relative group">
                                            <input type="text" 
                                                   id="site_width" 
                                                   name="site_width" 
                                                   data-rem-value="<?php echo esc_attr($settings['site_width_rem'] ?? '75rem'); ?>"
                                                   value="<?php echo esc_attr($settings['site_width_px'] ?? '1200px'); ?>"
                                                   placeholder="1200px"
                                                   class="nxw-size-input block w-full pl-4 pr-16 py-3.5 text-base text-gray-900 bg-white border-2 border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 placeholder:text-gray-400 hover:border-gray-300">
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                <span class="text-gray-400 text-sm font-medium">px</span>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex items-start space-x-2">
                                            <svg class="h-4 w-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-xs text-gray-600 leading-relaxed">
                                                <?php esc_html_e('Enter width value in pixels (px). Values are automatically converted to rem units in the background for responsive scaling. Default: 1200px (75rem).', 'nxw-page-builder'); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Padding Left Card -->
                                <div class="bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-100 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 bg-purple-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <label for="padding_left" class="block text-base font-semibold text-gray-900">
                                                    <?php esc_html_e('Left Padding', 'nxw-page-builder'); ?>
                                                </label>
                                                <p class="mt-1 text-sm text-gray-600">
                                                    <?php esc_html_e('Padding on the left side of content', 'nxw-page-builder'); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <div class="relative group">
                                            <input type="text" 
                                                   id="padding_left" 
                                                   name="padding_left" 
                                                   data-rem-value="<?php echo esc_attr($settings['padding_left_rem'] ?? '1rem'); ?>"
                                                   value="<?php echo esc_attr($settings['padding_left_px'] ?? '16px'); ?>"
                                                   placeholder="16px"
                                                   class="nxw-size-input block w-full pl-4 pr-16 py-3.5 text-base text-gray-900 bg-white border-2 border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 placeholder:text-gray-400 hover:border-gray-300">
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                <span class="text-gray-400 text-sm font-medium">px</span>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex items-start space-x-2">
                                            <svg class="h-4 w-4 text-purple-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-xs text-gray-600 leading-relaxed">
                                                <?php esc_html_e('Enter padding value in pixels (px). Values are automatically converted to rem units in the background for responsive scaling. Default: 16px (1rem).', 'nxw-page-builder'); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Padding Right Card -->
                                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-100 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 bg-emerald-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <label for="padding_right" class="block text-base font-semibold text-gray-900">
                                                    <?php esc_html_e('Right Padding', 'nxw-page-builder'); ?>
                                                </label>
                                                <p class="mt-1 text-sm text-gray-600">
                                                    <?php esc_html_e('Padding on the right side of content', 'nxw-page-builder'); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <div class="relative group">
                                            <input type="text" 
                                                   id="padding_right" 
                                                   name="padding_right" 
                                                   data-rem-value="<?php echo esc_attr($settings['padding_right_rem'] ?? '1rem'); ?>"
                                                   value="<?php echo esc_attr($settings['padding_right_px'] ?? '16px'); ?>"
                                                   placeholder="16px"
                                                   class="nxw-size-input block w-full pl-4 pr-16 py-3.5 text-base text-gray-900 bg-white border-2 border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 placeholder:text-gray-400 hover:border-gray-300">
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                <span class="text-gray-400 text-sm font-medium">px</span>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex items-start space-x-2">
                                            <svg class="h-4 w-4 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-xs text-gray-600 leading-relaxed">
                                                <?php esc_html_e('Enter padding value in pixels (px). Values are automatically converted to rem units in the background for responsive scaling. Default: 16px (1rem).', 'nxw-page-builder'); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Actions Footer -->
                    <div class="flex items-center justify-end space-x-3 pt-6 mt-8 border-t border-gray-200 bg-gray-50 -mx-8 px-8 py-6">
                        <button type="submit" 
                                class="nxw-btn-primary inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm hover:shadow-md transition-all duration-200"
                                data-loading-text="<?php esc_attr_e('Saving...', 'nxw-page-builder'); ?>">
                            <span class="button-text inline-flex items-center">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <?php esc_html_e('Save Settings', 'nxw-page-builder'); ?>
                            </span>
                            <span class="button-loader hidden inline-flex items-center">
                                <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="loader-text"><?php esc_html_e('Saving...', 'nxw-page-builder'); ?></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function($) {
    'use strict';
    
    // Site Settings form handler - user enters px, converts to rem before submit
    $(document).ready(function() {
        var $form = $('form[action*="nxw-page-builder-settings"]');
        
        if ($form.length === 0) {
            return;
        }
        
        // Auto-add 'px' to numeric inputs when user types just a number
        $form.find('.nxw-size-input').on('blur', function() {
            var $input = $(this);
            var value = $input.val().trim();
            
            if (!value) {
                return;
            }
            
            // If just a number, add 'px'
            if (/^[\d.]+$/.test(value)) {
                $input.val(value + 'px');
            }
        });
        
        // Convert px to rem before form submit (user sees px, but rem is saved)
        $form.on('submit', function(e) {
            var $form = $(this);
            
            // Get current font size from dropdown
            var fontSize = parseFloat($form.find('#default_font_size').val()) || 16;
            
            // Process each size input
            $form.find('.nxw-size-input').each(function() {
                var $input = $(this);
                var value = $input.val().trim();
                
                if (!value) {
                    return;
                }
                
                var pxValue = null;
                
                // Extract px value - remove 'px' if present, or just use number
                if (/^([\d.]+)px$/i.test(value)) {
                    pxValue = parseFloat(RegExp.$1);
                } else if (/^[\d.]+$/.test(value)) {
                    // Just a number, assume px
                    pxValue = parseFloat(value);
                }
                
                // Convert px to rem using current font size (save rem, user sees px)
                if (pxValue !== null && !isNaN(pxValue)) {
                    var remValue = (pxValue / fontSize).toFixed(3);
                    // Remove trailing zeros
                    remValue = parseFloat(remValue) + 'rem';
                    // Update input value to rem for form submission
                    $input.val(remValue);
                }
            });
        });
    });
})(jQuery);
</script>
