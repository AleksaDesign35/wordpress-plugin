<?php
declare(strict_types=1);

/**
 * Pages List Template
 *
 * @var array $pages
 */
?>

<div class="p-8">
    <!-- Actions Bar -->
    <div class="mb-8 flex justify-between items-start">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900"><?php esc_html_e('All Pages', 'nxw-page-builder'); ?></h2>
            <p class="mt-2 text-sm text-gray-500"><?php esc_html_e('Manage your page builder pages and their block configurations', 'nxw-page-builder'); ?></p>
        </div>
                <a href="<?php echo esc_url(admin_url('admin.php?page=nxw-page-builder-pages&action=add')); ?>"
           class="nxw-btn-primary inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <?php esc_html_e('Add New Page', 'nxw-page-builder'); ?>
        </a>
    </div>

    <!-- Pages Table -->
    <?php if (empty($pages)): ?>
        <div class="text-center py-16">
            <div class="mx-auto w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center">
                <svg class="h-12 w-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="mt-6 text-lg font-semibold text-gray-900"><?php esc_html_e('No pages found', 'nxw-page-builder'); ?></h3>
            <p class="mt-2 text-sm text-gray-500 max-w-md mx-auto"><?php esc_html_e('Get started by creating your first page builder page. Select a WordPress page and configure its blocks.', 'nxw-page-builder'); ?></p>
            <div class="mt-8">
                <a href="<?php echo esc_url(admin_url('admin.php?page=nxw-page-builder-pages&action=add')); ?>" 
                   class="nxw-btn-primary inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <?php esc_html_e('Add New Page', 'nxw-page-builder'); ?>
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <?php esc_html_e('Page', 'nxw-page-builder'); ?>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                                </svg>
                                <?php esc_html_e('Blocks', 'nxw-page-builder'); ?>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <?php esc_html_e('Actions', 'nxw-page-builder'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($pages as $page): ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">
                                            <?php echo esc_html($page['title']); ?>
                                        </div>
                                        <div class="text-sm text-gray-500 mt-0.5">
                                            <?php echo esc_html($page['slug']); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                    <svg class="h-3 w-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                                    </svg>
                                    <?php echo esc_html(count($page['blocks'])); ?> <?php esc_html_e('blocks', 'nxw-page-builder'); ?>
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="<?php echo esc_url($page['edit_url']); ?>" 
                                       class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <?php esc_html_e('Edit', 'nxw-page-builder'); ?>
                                    </a>
                                    <a href="<?php echo esc_url(get_permalink($page['id'])); ?>" 
                                       target="_blank"
                                       class="inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors duration-200">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <?php esc_html_e('View', 'nxw-page-builder'); ?>
                                    </a>
                                    <form method="post" 
                                          action="<?php echo esc_url(admin_url('admin.php?page=nxw-page-builder-pages')); ?>" 
                                          class="inline"
                                          onsubmit="return confirm('<?php esc_attr_e('Are you sure you want to delete this page?', 'nxw-page-builder'); ?>');">
                                        <?php wp_nonce_field('nxw-page-builder-nonce', 'nxw_nonce'); ?>
                                        <input type="hidden" name="nxw_action" value="delete_page">
                                        <input type="hidden" name="page_id" value="<?php echo esc_attr($page['id']); ?>">
                                        <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-800 transition-colors duration-200">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <?php esc_html_e('Delete', 'nxw-page-builder'); ?>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

