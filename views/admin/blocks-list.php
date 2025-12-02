<?php
declare(strict_types=1);

/**
 * Blocks List Template
 *
 * @var array $blocks
 * @var array $categories
 * @var \NXW\PageBuilder\Admin\BlocksManager $blocksManager
 */
?>

<div class="nxw-blocks-list-container flex h-[calc(100vh-120px)]">
    <!-- Sidebar with Categories -->
    <aside class="nxw-blocks-sidebar w-64 bg-white border-r border-gray-200 flex-shrink-0 overflow-y-auto">
        <div class="p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">
                <?php esc_html_e('Categories', 'nxw-page-builder'); ?>
            </h3>
            <nav class="space-y-1">
                <button 
                    class="nxw-category-filter w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-gray-900 bg-purple-50 border border-purple-200 hover:bg-purple-100 transition-colors"
                    data-category="all"
                >
                    <?php esc_html_e('All Blocks', 'nxw-page-builder'); ?>
                    <span class="nxw-category-count float-right text-gray-500">(<?php echo count($blocks); ?>)</span>
                </button>
                <?php foreach ($categories as $category): 
                    $categoryCount = count(array_filter($blocks, fn($b) => ($b['category'] ?? 'common') === $category));
                ?>
                    <button 
                        class="nxw-category-filter w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors"
                        data-category="<?php echo esc_attr($category); ?>"
                    >
                        <?php echo esc_html(ucfirst($category)); ?>
                        <span class="nxw-category-count float-right text-gray-500">(<?php echo $categoryCount; ?>)</span>
                    </button>
                <?php endforeach; ?>
            </nav>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <div class="px-8 pt-8 pb-4 border-b border-gray-200 flex-shrink-0">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900"><?php esc_html_e('All Blocks', 'nxw-page-builder'); ?></h2>
                <p class="mt-2 text-sm text-gray-500"><?php esc_html_e('Available blocks for your pages', 'nxw-page-builder'); ?></p>
            </div>
        </div>

        <!-- Blocks Grid -->
        <div class="flex-1 overflow-y-auto p-8">
            <?php if (empty($blocks)): ?>
                <div class="text-center py-16">
                    <div class="mx-auto w-24 h-24 bg-purple-50 rounded-full flex items-center justify-center">
                        <svg class="h-12 w-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                        </svg>
                    </div>
                    <h3 class="mt-6 text-lg font-semibold text-gray-900"><?php esc_html_e('No blocks found', 'nxw-page-builder'); ?></h3>
                    <p class="mt-2 text-sm text-gray-500 max-w-md mx-auto"><?php esc_html_e('Blocks will appear here once they are created in the Blocks directory.', 'nxw-page-builder'); ?></p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="nxw-blocks-grid">
                    <?php foreach ($blocks as $block): 
                        $blockCategory = $block['category'] ?? 'common';
                        $previewHtml = $blocksManager->renderBlockPreview($block['name']);
                    ?>
                        <div 
                            class="nxw-block-card bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-all duration-200 cursor-pointer"
                            data-category="<?php echo esc_attr($blockCategory); ?>"
                            data-block-name="<?php echo esc_attr($block['name']); ?>"
                        >
                            <!-- Block Preview Image/Preview -->
                            <div class="nxw-block-preview relative bg-gray-50 border-b border-gray-200 overflow-hidden" style="min-height: 200px; max-height: 200px;">
                                <?php if (!empty($previewHtml)): ?>
                                    <div class="nxw-block-preview-content scale-75 origin-top-left w-[133%] h-[133%] overflow-hidden pointer-events-none">
                                        <?php echo $previewHtml; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center justify-center h-full">
                                        <div class="flex-shrink-0 h-16 w-16 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center">
                                            <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Block Info -->
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center">
                                        <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                                        </svg>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <?php echo esc_html(ucfirst($blockCategory)); ?>
                                    </span>
                                </div>
                                
                                <h3 class="text-lg font-semibold text-gray-900 mb-2"><?php echo esc_html($block['title']); ?></h3>
                                
                                <?php if (!empty($block['description'])): ?>
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2"><?php echo esc_html($block['description']); ?></p>
                                <?php endif; ?>

                                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                    <div class="text-xs text-gray-500">
                                        <?php echo esc_html(count($block['attributes'])); ?> <?php esc_html_e('attributes', 'nxw-page-builder'); ?>
                                    </div>
                                    <span class="inline-flex items-center text-sm font-medium text-blue-600">
                                        <?php esc_html_e('Available', 'nxw-page-builder'); ?>
                                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div id="nxw-block-preview-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" id="nxw-modal-overlay"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
            <div class="bg-white">
                <!-- Modal Header -->
                <div class="px-6 pt-6 pb-4 border-b border-gray-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-900" id="nxw-preview-title"></h3>
                            <p class="mt-1 text-sm text-gray-500" id="nxw-preview-description"></p>
                        </div>
                        <button type="button" class="ml-4 text-gray-400 hover:text-gray-500" id="nxw-close-preview">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="flex px-6" aria-label="Tabs">
                        <button class="nxw-modal-tab active px-4 py-3 text-sm font-medium text-purple-600 border-b-2 border-purple-600" data-tab="preview">
                            <?php esc_html_e('Preview', 'nxw-page-builder'); ?>
                        </button>
                        <button class="nxw-modal-tab px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300" data-tab="attributes">
                            <?php esc_html_e('Parameters', 'nxw-page-builder'); ?>
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="px-6 py-6">
                    <!-- Preview Tab -->
                    <div id="nxw-tab-preview" class="nxw-tab-content">
                        <div class="nxw-preview-content bg-gray-50 rounded-lg p-4 overflow-auto" style="max-height: 60vh;">
                            <!-- Preview content will be loaded here -->
                        </div>
                    </div>

                    <!-- Attributes Tab -->
                    <div id="nxw-tab-attributes" class="nxw-tab-content hidden">
                        <div class="overflow-auto" style="max-height: 60vh;">
                            <div id="nxw-attributes-list" class="space-y-4">
                                <!-- Attributes will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    // Category filtering
    const categoryFilters = document.querySelectorAll('.nxw-category-filter');
    const blockCards = document.querySelectorAll('.nxw-block-card');
    
    categoryFilters.forEach(filter => {
        filter.addEventListener('click', function() {
            const selectedCategory = this.getAttribute('data-category');
            
            // Update active state
            categoryFilters.forEach(f => {
                f.classList.remove('bg-purple-50', 'border', 'border-purple-200', 'text-gray-900');
                f.classList.add('text-gray-700');
            });
            this.classList.add('bg-purple-50', 'border', 'border-purple-200', 'text-gray-900');
            this.classList.remove('text-gray-700');
            
            // Filter blocks
            blockCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                if (selectedCategory === 'all' || cardCategory === selectedCategory) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Update counts
            updateCategoryCounts();
        });
    });
    
    function updateCategoryCounts() {
        const visibleBlocks = Array.from(blockCards).filter(card => card.style.display !== 'none');
        const allCount = document.querySelector('[data-category="all"] .nxw-category-count');
        if (allCount) {
            allCount.textContent = '(' + visibleBlocks.length + ')';
        }
    }
    
    // Preview modal
    const previewModal = document.getElementById('nxw-block-preview-modal');
    const previewTitle = document.getElementById('nxw-preview-title');
    const previewDescription = document.getElementById('nxw-preview-description');
    const previewContent = document.querySelector('.nxw-preview-content');
    const attributesList = document.getElementById('nxw-attributes-list');
    const closePreview = document.getElementById('nxw-close-preview');
    const modalOverlay = document.getElementById('nxw-modal-overlay');
    const modalTabs = document.querySelectorAll('.nxw-modal-tab');
    const tabContents = document.querySelectorAll('.nxw-tab-content');
    
    // Tab switching
    modalTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Update tab buttons
            modalTabs.forEach(t => {
                t.classList.remove('active', 'text-purple-600', 'border-purple-600');
                t.classList.add('text-gray-500', 'border-transparent');
            });
            this.classList.add('active', 'text-purple-600', 'border-purple-600');
            this.classList.remove('text-gray-500', 'border-transparent');
            
            // Update tab content
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById('nxw-tab-' + targetTab).classList.remove('hidden');
        });
    });
    
    blockCards.forEach(card => {
        card.addEventListener('click', function() {
            const blockName = this.getAttribute('data-block-name');
            
            // Fetch preview via AJAX
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'nxw_get_block_preview',
                    block_name: blockName,
                    nonce: '<?php echo wp_create_nonce('nxw_block_preview_nonce'); ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Set title and description
                    previewTitle.textContent = data.data.title || blockName;
                    previewDescription.textContent = data.data.description || '';
                    
                    // Set preview content
                    previewContent.innerHTML = data.data.preview || '<p class="text-gray-500"><?php esc_html_e('Preview not available', 'nxw-page-builder'); ?></p>';
                    
                    // Set attributes
                    if (data.data.attributes && data.data.attributes.length > 0) {
                        // Escape HTML function
                        const escapeHtml = (text) => {
                            const div = document.createElement('div');
                            div.textContent = text;
                            return div.innerHTML;
                        };
                        
                        let attributesHtml = '<div class="space-y-3">';
                        data.data.attributes.forEach(attr => {
                            const attrName = escapeHtml(attr.name);
                            const attrType = escapeHtml(attr.type);
                            const attrDesc = attr.description ? escapeHtml(attr.description) : '';
                            
                            let defaultValue;
                            if (attr.default !== null && attr.default !== undefined) {
                                if (typeof attr.default === 'object') {
                                    defaultValue = escapeHtml(JSON.stringify(attr.default, null, 2));
                                } else {
                                    defaultValue = escapeHtml(String(attr.default));
                                }
                            } else {
                                defaultValue = '<span class="text-gray-400 italic"><?php esc_html_e('No default', 'nxw-page-builder'); ?></span>';
                            }
                            
                            attributesHtml += `
                                <div class="bg-white border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-semibold text-gray-900 font-mono">${attrName}</h4>
                                            ${attrDesc ? `<p class="mt-1 text-xs text-gray-600">${attrDesc}</p>` : ''}
                                        </div>
                                        <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            ${attrType}
                                        </span>
                                    </div>
                                    <div class="mt-2 pt-2 border-t border-gray-100">
                                        <div class="text-xs text-gray-500 mb-1"><?php esc_html_e('Default:', 'nxw-page-builder'); ?></div>
                                        <div class="text-sm text-gray-900 font-mono bg-gray-50 p-2 rounded break-all whitespace-pre-wrap">${defaultValue}</div>
                                    </div>
                                </div>
                            `;
                        });
                        attributesHtml += '</div>';
                        attributesList.innerHTML = attributesHtml;
                    } else {
                        attributesList.innerHTML = '<p class="text-gray-500 text-center py-8"><?php esc_html_e('No parameters available', 'nxw-page-builder'); ?></p>';
                    }
                    
                    // Reset to preview tab
                    modalTabs.forEach(t => {
                        t.classList.remove('active', 'text-purple-600', 'border-purple-600');
                        t.classList.add('text-gray-500', 'border-transparent');
                    });
                    document.querySelector('[data-tab="preview"]').classList.add('active', 'text-purple-600', 'border-purple-600');
                    document.querySelector('[data-tab="preview"]').classList.remove('text-gray-500', 'border-transparent');
                    
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    document.getElementById('nxw-tab-preview').classList.remove('hidden');
                    
                    // Show modal
                    previewModal.classList.remove('hidden');
                } else {
                    alert(data.data?.message || '<?php esc_html_e('Error loading preview', 'nxw-page-builder'); ?>');
                }
            })
            .catch(error => {
                console.error('Error loading preview:', error);
                alert('<?php esc_html_e('Error loading preview', 'nxw-page-builder'); ?>');
            });
        });
    });
    
    function closeModal() {
        previewModal.classList.add('hidden');
    }
    
    closePreview.addEventListener('click', closeModal);
    modalOverlay.addEventListener('click', closeModal);
    
    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !previewModal.classList.contains('hidden')) {
            closeModal();
        }
    });
})();
</script>

