<?php
declare(strict_types=1);

/**
 * Site Settings Page
 * @var array $settings Site settings
 */

// Get active tab from query string or default to 'layout'
$activeTab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'layout';

// Display notices
$notice = get_transient('nxw_page_builder_notice_' . get_current_user_id());
$noticeHtml = '';
if ($notice) {
    $noticeType = $notice['type'] ?? 'success';
    $noticeMessage = $notice['message'] ?? '';
    $noticeHtml = '<div class="notice notice-' . esc_attr($noticeType) . ' is-dismissible mb-6 p-4"><p>' . esc_html($noticeMessage) . '</p></div>';
    delete_transient('nxw_page_builder_notice_' . get_current_user_id());
}
?>

<div class="flex">
    <!-- Sidebar Navigation -->
    <div class="w-64 bg-gray-50 border-r border-gray-200">
        <nav class="p-4 space-y-1">
            <a href="<?php echo esc_url(admin_url('admin.php?page=nxw-page-builder-settings&tab=layout')); ?>" 
               class="nxw-settings-tab flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 <?php echo $activeTab === 'layout' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-gray-700 hover:bg-gray-100'; ?>">
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <?php esc_html_e('Layout', 'nxw-page-builder'); ?>
            </a>
            
            <a href="<?php echo esc_url(admin_url('admin.php?page=nxw-page-builder-settings&tab=typography')); ?>" 
               class="nxw-settings-tab flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 <?php echo $activeTab === 'typography' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-gray-700 hover:bg-gray-100'; ?>">
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <?php esc_html_e('Typography', 'nxw-page-builder'); ?>
            </a>
            
            <a href="<?php echo esc_url(admin_url('admin.php?page=nxw-page-builder-settings&tab=colors')); ?>" 
               class="nxw-settings-tab flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 <?php echo $activeTab === 'colors' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-gray-700 hover:bg-gray-100'; ?>">
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                </svg>
                <?php esc_html_e('Colors', 'nxw-page-builder'); ?>
            </a>
        </nav>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1">
        <?php echo $noticeHtml; ?>
        <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=nxw-page-builder-settings&tab=' . esc_attr($activeTab))); ?>" id="nxw-site-settings-form" class="p-8">
            <?php wp_nonce_field('nxw_site_settings_nonce', '_wpnonce'); ?>
            <input type="hidden" name="page" value="nxw-page-builder-settings">
            <input type="hidden" name="nxw_action" value="save_site_settings">
            <input type="hidden" name="tab" value="<?php echo esc_attr($activeTab); ?>">

            <?php if ($activeTab === 'layout'): ?>
                <!-- Layout Tab -->
                <div class="space-y-8">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-6"><?php esc_html_e('Layout Settings', 'nxw-page-builder'); ?></h2>
                        
                        <!-- Site Width -->
                        <div class="space-y-3 mb-6">
                            <label for="site_width" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                <?php esc_html_e('Site Width', 'nxw-page-builder'); ?>
                            </label>
                            
                            <div class="relative max-w-md">
                                <input type="text" 
                                       id="site_width" 
                                       name="site_width" 
                                       value="<?php echo esc_attr($settings['site_width']); ?>" 
                                       placeholder="75rem or 1200px"
                                       class="nxw-size-input block w-full px-4 py-3 pr-20 text-base text-gray-900 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-sm text-gray-500 unit-indicator">rem</span>
                                </div>
                            </div>
                            
                            <p class="text-sm text-gray-500">
                                <?php esc_html_e('Maximum width of the site container. Enter value in px (e.g., 1200px) or rem (e.g., 75rem). Px values will be automatically converted to rem.', 'nxw-page-builder'); ?>
                            </p>
                        </div>

                        <!-- Padding Left -->
                        <div class="space-y-3 mb-6">
                            <label for="padding_left" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                                </svg>
                                <?php esc_html_e('Padding Left', 'nxw-page-builder'); ?>
                            </label>
                            
                            <div class="relative max-w-md">
                                <input type="text" 
                                       id="padding_left" 
                                       name="padding_left" 
                                       value="<?php echo esc_attr($settings['padding_left']); ?>" 
                                       placeholder="1rem or 16px"
                                       class="nxw-size-input block w-full px-4 py-3 pr-20 text-base text-gray-900 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-sm text-gray-500 unit-indicator">rem</span>
                                </div>
                            </div>
                            
                            <p class="text-sm text-gray-500">
                                <?php esc_html_e('Left padding for site container. Enter value in px (e.g., 16px) or rem (e.g., 1rem). Px values will be automatically converted to rem.', 'nxw-page-builder'); ?>
                            </p>
                        </div>

                        <!-- Padding Right -->
                        <div class="space-y-3 mb-6">
                            <label for="padding_right" class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                </svg>
                                <?php esc_html_e('Padding Right', 'nxw-page-builder'); ?>
                            </label>
                            
                            <div class="relative max-w-md">
                                <input type="text" 
                                       id="padding_right" 
                                       name="padding_right" 
                                       value="<?php echo esc_attr($settings['padding_right']); ?>" 
                                       placeholder="1rem or 16px"
                                       class="nxw-size-input block w-full px-4 py-3 pr-20 text-base text-gray-900 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-sm text-gray-500 unit-indicator">rem</span>
                                </div>
                            </div>
                            
                            <p class="text-sm text-gray-500">
                                <?php esc_html_e('Right padding for site container. Enter value in px (e.g., 16px) or rem (e.g., 1rem). Px values will be automatically converted to rem.', 'nxw-page-builder'); ?>
                            </p>
                        </div>
                    </div>
                </div>

            <?php elseif ($activeTab === 'typography'): ?>
                <!-- Typography Tab -->
                <div class="space-y-8">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-6"><?php esc_html_e('Typography Settings', 'nxw-page-builder'); ?></h2>
                        <p class="text-gray-500"><?php esc_html_e('Typography options will be available here soon.', 'nxw-page-builder'); ?></p>
                    </div>
                </div>

            <?php elseif ($activeTab === 'colors'): ?>
                <!-- Colors Tab -->
                <div class="space-y-8">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-6"><?php esc_html_e('Color Settings', 'nxw-page-builder'); ?></h2>
                        <p class="text-gray-500"><?php esc_html_e('Color options will be available here soon.', 'nxw-page-builder'); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 mt-8 pt-8 border-t border-gray-200">
                <button type="submit" 
                        name="nxw_save_settings"
                        class="nxw-btn-primary inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-all duration-200"
                        data-loading-text="<?php esc_attr_e('Saving...', 'nxw-page-builder'); ?>">
                    <span class="button-text">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <?php esc_html_e('Save Settings', 'nxw-page-builder'); ?>
                    </span>
                    <span class="button-loader hidden">
                        <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
