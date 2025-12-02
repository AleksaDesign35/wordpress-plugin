<?php
declare(strict_types=1);

/**
 * Page Blocks Editor Template
 *
 * @var array $page
 * @var int $pageId
 * @var array $availableBlocks
 */
?>

<div class="p-8">
    <!-- Header -->
    <div class="mb-8 pb-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="<?php echo esc_url(admin_url('admin.php?page=nxw-page-builder-pages')); ?>" 
                   class="mr-4 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">
                        <?php echo esc_html($page['title']); ?>
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        <?php esc_html_e('Configure blocks for this page', 'nxw-page-builder'); ?>
                    </p>
                </div>
            </div>
            <a href="<?php echo esc_url(get_permalink($pageId)); ?>" 
               target="_blank"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <?php esc_html_e('View Page', 'nxw-page-builder'); ?>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Available Blocks Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-gray-200 rounded-xl p-6 sticky top-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <?php esc_html_e('Available Blocks', 'nxw-page-builder'); ?>
                </h3>
                
                <?php if (empty($availableBlocks)): ?>
                    <p class="text-sm text-gray-500">
                        <?php esc_html_e('No blocks available yet.', 'nxw-page-builder'); ?>
                    </p>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($availableBlocks as $block): ?>
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-sm transition-all cursor-move"
                                 data-block-name="<?php echo esc_attr($block['name']); ?>"
                                 draggable="true">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo esc_html($block['title']); ?>
                                        </div>
                                        <?php if (!empty($block['description'])): ?>
                                            <div class="text-xs text-gray-500 mt-1">
                                                <?php echo esc_html($block['description']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Blocks Editor Area -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <?php esc_html_e('Page Blocks', 'nxw-page-builder'); ?>
                    </h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                        <?php echo esc_html(count($page['blocks'])); ?> <?php esc_html_e('blocks', 'nxw-page-builder'); ?>
                    </span>
                </div>

                <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=nxw-page-builder-pages&action=edit-blocks&page_id=' . $pageId)); ?>">
                    <?php wp_nonce_field('nxw-page-builder-nonce', 'nxw_nonce'); ?>
                    <input type="hidden" name="nxw_action" value="save_page_blocks">
                    <input type="hidden" name="page_id" value="<?php echo esc_attr($pageId); ?>">
                    <input type="hidden" name="wordpress_page_id" value="<?php echo esc_attr($pageId); ?>">

                    <!-- Blocks Container -->
                    <div id="nxw-blocks-container" class="space-y-4 min-h-[400px] border-2 border-dashed border-transparent rounded-lg p-4 transition-colors">
                        <?php if (empty($page['blocks'])): ?>
                            <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                                </svg>
                                <h3 class="mt-4 text-sm font-medium text-gray-900">
                                    <?php esc_html_e('No blocks added yet', 'nxw-page-builder'); ?>
                                </h3>
                                <p class="mt-2 text-sm text-gray-500">
                                    <?php esc_html_e('Click on a block from the sidebar to add it to this page.', 'nxw-page-builder'); ?>
                                </p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($page['blocks'] as $index => $block): 
                                $blockName = $block['name'] ?? '';
                                $blockAttrs = $block['attributes'] ?? [];
                                $blockTitle = $blockAttrs['title'] ?? ucfirst($blockName);
                                $blockDescription = '';
                                foreach ($availableBlocks as $avBlock) {
                                    if ($avBlock['name'] === $blockName) {
                                        $blockDescription = $avBlock['description'] ?? '';
                                        break;
                                    }
                                }
                            ?>
                                <div class="block-item border border-gray-200 rounded-lg p-4 bg-white hover:shadow-md transition-shadow" data-block-index="<?php echo esc_attr($index); ?>" draggable="true">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start flex-1">
                                            <div class="drag-handle cursor-move mr-3 mt-1 text-gray-400 hover:text-gray-600">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-semibold text-gray-900"><?php echo esc_html(ucfirst($blockName)); ?></div>
                                                        <?php if ($blockDescription): ?>
                                                            <div class="text-xs text-gray-500"><?php echo esc_html($blockDescription); ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <?php if ($blockName === 'Hero'): ?>
                                                    <div class="mt-3 p-4 rounded-lg border border-gray-200 bg-gradient-to-br from-blue-50 to-blue-100">
                                                        <div class="text-center">
                                                            <div class="text-lg font-bold text-gray-900 mb-2"><?php echo esc_html($blockAttrs['title'] ?? 'Welcome to Our Website'); ?></div>
                                                            <div class="text-sm text-gray-700 mb-3"><?php echo esc_html($blockAttrs['subtitle'] ?? 'Create amazing experiences'); ?></div>
                                                            <div class="inline-block px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded">
                                                                <?php echo esc_html($blockAttrs['buttonText'] ?? 'Get Started'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <button type="button" class="text-red-600 hover:text-red-800 remove-block ml-3">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <input type="hidden" name="blocks[<?php echo esc_attr($index); ?>][name]" value="<?php echo esc_attr($blockName); ?>">
                                    <input type="hidden" name="blocks[<?php echo esc_attr($index); ?>][attributes]" value="<?php echo esc_attr(json_encode($blockAttrs)); ?>">
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=nxw-page-builder-pages')); ?>" 
                           class="nxw-btn-secondary inline-flex items-center px-5 py-2.5 text-sm font-medium">
                            <?php esc_html_e('Cancel', 'nxw-page-builder'); ?>
                        </a>
                        <button type="submit" 
                                class="nxw-btn-primary inline-flex items-center px-5 py-2.5 text-sm font-medium">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <?php esc_html_e('Save Blocks', 'nxw-page-builder'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

