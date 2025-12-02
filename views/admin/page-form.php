<?php
declare(strict_types=1);

/**
 * Page Form Template
 *
 * @var array|null $page
 * @var int $pageId
 * @var array $wordpressPages
 */
?>

<div class="p-8">
    <!-- Header -->
    <div class="mb-8 pb-6 border-b border-gray-200">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <?php if ($pageId > 0): ?>
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                <?php else: ?>
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                <?php endif; ?>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-semibold text-gray-900">
                    <?php echo $pageId > 0 
                        ? esc_html__('Edit Page', 'nxw-page-builder') 
                        : esc_html__('Add New Page', 'nxw-page-builder'); ?>
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    <?php esc_html_e('Select a WordPress page and configure its blocks', 'nxw-page-builder'); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=nxw-page-builder-pages')); ?>" class="space-y-8">
        <?php wp_nonce_field('nxw-page-builder-nonce', 'nxw_nonce'); ?>
        <input type="hidden" name="nxw_action" value="save_page">
        
        <?php if ($pageId > 0): ?>
            <input type="hidden" name="page_id" value="<?php echo esc_attr($pageId); ?>">
        <?php endif; ?>

        <!-- WordPress Page Selection -->
        <div class="space-y-3">
            <label for="wordpress_page_id" class="flex items-center text-sm font-semibold text-gray-700">
                <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <?php esc_html_e('WordPress Page', 'nxw-page-builder'); ?>
                <span class="ml-1 text-red-500">*</span>
            </label>
            
            <div class="flex gap-3">
                <div class="flex-1 relative">
                    <select id="wordpress_page_id" 
                            name="wordpress_page_id" 
                            <?php if ($pageId <= 0): ?>required<?php endif; ?>
                            class="nxw-select block w-full px-4 py-3 text-base text-gray-900 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 appearance-none cursor-pointer">
                        <option value=""><?php esc_html_e('-- Select a page --', 'nxw-page-builder'); ?></option>
                        <option value="new" <?php selected(!$page && isset($_GET['new_page'])); ?>>
                            <?php esc_html_e('+ Create New Page', 'nxw-page-builder'); ?>
                        </option>
                        <?php foreach ($wordpressPages as $wpPage): ?>
                            <option value="<?php echo esc_attr($wpPage->ID); ?>" 
                                    <?php selected($page && $page['wordpress_page_id'] == $wpPage->ID); ?>>
                                <?php echo esc_html($wpPage->post_title); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div id="new-page-fields" class="flex-1 space-y-3 hidden">
                    <input type="text" 
                           id="new_page_title" 
                           name="new_page_title" 
                           placeholder="<?php esc_attr_e('New Page Title', 'nxw-page-builder'); ?>"
                           class="block w-full px-4 py-3 text-base text-gray-900 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
            
            <p class="flex items-start text-sm text-gray-500">
                <svg class="h-4 w-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span><?php esc_html_e('Choose an existing WordPress page or create a new one.', 'nxw-page-builder'); ?></span>
            </p>
        </div>

        <!-- Blocks Section (Placeholder) -->
        <div class="space-y-3">
            <label class="flex items-center text-sm font-semibold text-gray-700">
                <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                </svg>
                <?php esc_html_e('Blocks', 'nxw-page-builder'); ?>
            </label>
            
            <div class="relative overflow-hidden rounded-lg border-2 border-dashed border-gray-300 bg-gradient-to-br from-gray-50 to-gray-100/50 p-8">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                    </svg>
                    <h3 class="mt-4 text-sm font-medium text-gray-900"><?php esc_html_e('Blocks Configuration', 'nxw-page-builder'); ?></h3>
                    <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">
                        <?php esc_html_e('Block configuration will be available here. This feature will be implemented next.', 'nxw-page-builder'); ?>
                    </p>
                </div>
            </div>
                <input type="hidden" name="blocks" value='[]'>
            </div>

            <!-- Theme Header/Footer Options -->
            <div class="space-y-4 pt-6 border-t border-gray-200">
                <label class="flex items-center text-sm font-semibold text-gray-700 mb-4">
                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <?php esc_html_e('Theme Integration', 'nxw-page-builder'); ?>
                </label>

                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="use_theme_header" 
                               value="1"
                               <?php checked($page && get_post_meta($pageId, '_nxw_use_theme_header', true) === '1'); ?>
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-3 text-sm text-gray-700">
                            <?php esc_html_e('Use theme header', 'nxw-page-builder'); ?>
                        </span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="use_theme_footer" 
                               value="1"
                               <?php checked($page && get_post_meta($pageId, '_nxw_use_theme_footer', true) === '1'); ?>
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-3 text-sm text-gray-700">
                            <?php esc_html_e('Use theme footer', 'nxw-page-builder'); ?>
                        </span>
                    </label>
                </div>

                <p class="text-sm text-gray-500">
                    <?php esc_html_e('By default, pages are rendered completely blank. Enable these options to use your theme\'s header and/or footer.', 'nxw-page-builder'); ?>
                </p>
            </div>

            <!-- Actions -->
        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="<?php echo esc_url(admin_url('admin.php?page=nxw-page-builder-pages')); ?>"
               class="nxw-btn-secondary inline-flex items-center px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <?php esc_html_e('Cancel', 'nxw-page-builder'); ?>
            </a>
            <button type="submit" 
                    class="nxw-btn-primary inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-all duration-200"
                    data-loading-text="<?php esc_attr_e('Saving...', 'nxw-page-builder'); ?>">
                <span class="button-text">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <?php esc_html_e('Save Page', 'nxw-page-builder'); ?>
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

