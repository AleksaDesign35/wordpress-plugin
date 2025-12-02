<?php
declare(strict_types=1);

/**
 * Dashboard Template
 *
 * @var int $totalPages
 * @var int $totalBlocks
 * @var array $recentPages
 */
?>

<div class="p-8">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-900 mb-2"><?php esc_html_e('Welcome to NXW Page Builder', 'nxw-page-builder'); ?></h2>
        <p class="text-gray-600"><?php esc_html_e('Create beautiful pages with modular blocks', 'nxw-page-builder'); ?></p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Pages Card -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600 uppercase tracking-wide"><?php esc_html_e('Total Pages', 'nxw-page-builder'); ?></p>
                    <p class="mt-2 text-4xl font-bold text-blue-900"><?php echo esc_html($totalPages); ?></p>
                </div>
                <div class="h-16 w-16 bg-blue-500 rounded-xl flex items-center justify-center">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="<?php echo esc_url(admin_url('admin.php?page=nxw-page-builder-pages&action=add')); ?>" 
                   class="text-sm font-medium text-blue-700 hover:text-blue-800 inline-flex items-center">
                    <?php esc_html_e('Add New Page', 'nxw-page-builder'); ?>
                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Blocks Card -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-600 uppercase tracking-wide"><?php esc_html_e('Available Blocks', 'nxw-page-builder'); ?></p>
                    <p class="mt-2 text-4xl font-bold text-purple-900"><?php echo esc_html($totalBlocks); ?></p>
                </div>
                <div class="h-16 w-16 bg-purple-500 rounded-xl flex items-center justify-center">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="<?php echo esc_url(admin_url('admin.php?page=nxw-page-builder-blocks')); ?>" 
                   class="text-sm font-medium text-purple-700 hover:text-purple-800 inline-flex items-center">
                    <?php esc_html_e('View All Blocks', 'nxw-page-builder'); ?>
                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Pages -->
    <?php if (!empty($recentPages)): ?>
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900"><?php esc_html_e('Recent Pages', 'nxw-page-builder'); ?></h3>
            </div>
            <div class="divide-y divide-gray-200">
                <?php foreach ($recentPages as $page): ?>
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900"><?php echo esc_html($page['title']); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo esc_html($page['slug']); ?></div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                    <?php echo esc_html(count($page['blocks'])); ?> <?php esc_html_e('blocks', 'nxw-page-builder'); ?>
                                </span>
                                <a href="<?php echo esc_url($page['edit_url']); ?>" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    <?php esc_html_e('Edit', 'nxw-page-builder'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <a href="<?php echo esc_url(admin_url('admin.php?page=nxw-page-builder-pages')); ?>" 
                   class="text-sm font-medium text-gray-700 hover:text-gray-900 inline-flex items-center">
                    <?php esc_html_e('View all pages', 'nxw-page-builder'); ?>
                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-semibold text-gray-900"><?php esc_html_e('No pages yet', 'nxw-page-builder'); ?></h3>
            <p class="mt-2 text-sm text-gray-500"><?php esc_html_e('Get started by creating your first page.', 'nxw-page-builder'); ?></p>
            <div class="mt-6">
                <a href="<?php echo esc_url(admin_url('admin.php?page=nxw-page-builder-pages&action=add')); ?>" 
                   class="nxw-btn-primary inline-flex items-center px-5 py-2.5 text-sm font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <?php esc_html_e('Create Your First Page', 'nxw-page-builder'); ?>
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

