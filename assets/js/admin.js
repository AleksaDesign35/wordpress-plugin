/**
 * NXW Page Builder Admin JS
 */

(function($) {
    'use strict';

    // Global namespace
    window.NXW = window.NXW || {};

    // Admin module
    NXW.modules = NXW.modules || {};
    
    NXW.modules.admin = {
        /**
         * Initialize admin module
         */
        init: function() {
            this.bindEvents();
            this.initBlocksEditor();
            this.initUnitIndicators();
        },

        /**
         * Bind event handlers
         */
        bindEvents: function() {
            var self = this;
            
            // Handle form submissions with loader
            $('.nxw-page-builder-wrap form').on('submit', function(e) {
                var $form = $(this);
                var $submitBtn = $form.find('button[type="submit"], input[type="submit"]');
                
                // Auto-add 'px' to numeric inputs before submit
                self.autoFormatSizeInputs($form);
                
                // Show loader
                if ($submitBtn.length) {
                    self.showLoader($submitBtn);
                }
            });

            // Handle size input conversion (px to rem) and unit indicator
            $(document).on('input blur', '.nxw-size-input', function() {
                var $input = $(this);
                var value = $input.val().trim();
                var $indicator = $input.siblings('.unit-indicator');
                
                if (!value) {
                    return;
                }
                
                // Check if value is in px format
                if (/^[\d.]+px$/i.test(value)) {
                    var pxValue = parseFloat(value);
                    var remValue = (pxValue / 16).toFixed(3).replace(/\.?0+$/, '');
                    $input.val(remValue + 'rem');
                    if ($indicator.length) {
                        $indicator.text('rem');
                    }
                } else if (/^[\d.]+rem$/i.test(value)) {
                    // Already in rem, just update indicator
                    if ($indicator.length) {
                        $indicator.text('rem');
                    }
                } else if (/^[\d.]+$/.test(value)) {
                    // Just a number, assume px and convert
                    var pxValue = parseFloat(value);
                    var remValue = (pxValue / 16).toFixed(3).replace(/\.?0+$/, '');
                    $input.val(remValue + 'rem');
                    if ($indicator.length) {
                        $indicator.text('rem');
                    }
                }
            });
            
            // Initialize unit indicators on page load
            this.initUnitIndicators();

            // Handle delete confirmations
            $('form[data-action="delete"]').on('submit', this.handleDeleteConfirm);
            
            // Handle new page creation
            $('#wordpress_page_id').on('change', function() {
                if ($(this).val() === 'new') {
                    $('#new-page-fields').removeClass('hidden');
                    $('#new_page_title').prop('required', true);
                } else {
                    $('#new-page-fields').addClass('hidden');
                    $('#new_page_title').prop('required', false);
                }
            });
        },

        /**
         * Initialize blocks editor
         */
        initBlocksEditor: function() {
            var self = this;
            var $container = $('#nxw-blocks-container');
            
            if ($container.length === 0) {
                return;
            }

            // Make blocks container droppable
            this.initDragAndDrop();

            // Add block on click
            $('[data-block-name]').on('click', function() {
                var blockName = $(this).data('block-name');
                var blockTitle = $(this).find('.text-sm.font-medium').first().text();
                var blockDescription = $(this).find('.text-xs.text-gray-500').first().text();
                
                self.addBlock(blockName, blockTitle, blockDescription);
            });

            // Remove block
            $(document).on('click', '.remove-block', function(e) {
                e.preventDefault();
                $(this).closest('.block-item').fadeOut(300, function() {
                    $(this).remove();
                    self.updateBlocksCounter();
                    self.updateEmptyState();
                });
            });

            // Make blocks sortable
            if ($.fn.sortable) {
                $container.sortable({
                    handle: '.drag-handle',
                    placeholder: 'sortable-placeholder',
                    tolerance: 'pointer',
                    cursor: 'move',
                    opacity: 0.8,
                    update: function() {
                        self.updateBlockIndices();
                    }
                });
            }
        },

        /**
         * Initialize drag and drop
         */
        initDragAndDrop: function() {
            var $container = $('#nxw-blocks-container');
            
            // Make available blocks draggable
            $('[data-block-name]').each(function() {
                this.draggable = true;
                this.addEventListener('dragstart', function(e) {
                    var blockName = $(this).data('block-name');
                    var blockTitle = $(this).find('.text-sm.font-medium').first().text();
                    var blockDescription = $(this).find('.text-xs.text-gray-500').first().text();
                    
                    e.dataTransfer.setData('text/plain', JSON.stringify({
                        name: blockName,
                        title: blockTitle,
                        description: blockDescription
                    }));
                    $(this).addClass('opacity-50');
                });
                
                this.addEventListener('dragend', function() {
                    $(this).removeClass('opacity-50');
                });
            });

            // Make container droppable
            $container[0].addEventListener('dragover', function(e) {
                e.preventDefault();
                $(this).addClass('border-blue-300 bg-blue-50');
            });
            
            $container[0].addEventListener('dragleave', function(e) {
                $(this).removeClass('border-blue-300 bg-blue-50');
            });
            
            $container[0].addEventListener('drop', function(e) {
                e.preventDefault();
                $(this).removeClass('border-blue-300 bg-blue-50');
                
                try {
                    var blockData = JSON.parse(e.dataTransfer.getData('text/plain'));
                    NXW.modules.admin.addBlock(blockData.name, blockData.title, blockData.description);
                } catch(err) {
                    console.error('Error parsing block data:', err);
                }
            });
        },

        /**
         * Add block to editor
         */
        addBlock: function(blockName, blockTitle, blockDescription) {
            var $container = $('#nxw-blocks-container');
            var blockCount = $container.find('.block-item').length;
            var blockIndex = blockCount;
            
            // Remove empty state if exists
            $container.find('.text-center').remove();
            
            // Get block preview HTML
            var blockHtml = this.getBlockPreviewHtml(blockName, blockTitle, blockDescription, blockIndex);
            
            $container.append(blockHtml);
            this.updateBlocksCounter();
            
            // Scroll to new block
            $container.find('.block-item').last()[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        },

        /**
         * Get block preview HTML
         */
        getBlockPreviewHtml: function(blockName, blockTitle, blockDescription, index) {
            // Get block attributes from block.json if available
            var blockData = {
                name: blockName,
                title: blockTitle,
                description: blockDescription,
                attributes: {}
            };
            
            // Hero block specific preview
            if (blockName === 'Hero') {
                blockData.attributes = {
                    title: 'Welcome to Our Website',
                    subtitle: 'Create amazing experiences with our page builder',
                    buttonText: 'Get Started',
                    buttonLink: '#',
                    backgroundColor: '#3b82f6',
                    textColor: '#ffffff',
                    alignment: 'center',
                    padding: 'large'
                };
            }
            
            var attributesJson = JSON.stringify(blockData.attributes);
            
            return `
                <div class="block-item border border-gray-200 rounded-lg p-4 bg-white hover:shadow-md transition-shadow" data-block-index="${index}" draggable="true">
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
                                        <div class="text-sm font-semibold text-gray-900">${this.escapeHtml(blockTitle)}</div>
                                        <div class="text-xs text-gray-500">${this.escapeHtml(blockDescription)}</div>
                                    </div>
                                </div>
                                ${this.getBlockPreviewContent(blockName, blockData.attributes)}
                            </div>
                        </div>
                        <button type="button" class="text-red-600 hover:text-red-800 remove-block ml-3">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" name="blocks[${index}][name]" value="${this.escapeHtml(blockName)}">
                    <input type="hidden" name="blocks[${index}][attributes]" value='${this.escapeHtml(attributesJson)}'>
                </div>
            `;
        },

        /**
         * Get block preview content
         */
        getBlockPreviewContent: function(blockName, attributes) {
            if (blockName === 'Hero') {
                return `
                    <div class="mt-3 p-4 rounded-lg border border-gray-200 bg-gradient-to-br from-blue-50 to-blue-100">
                        <div class="text-center">
                            <div class="text-lg font-bold text-gray-900 mb-2">${this.escapeHtml(attributes.title || 'Welcome to Our Website')}</div>
                            <div class="text-sm text-gray-700 mb-3">${this.escapeHtml(attributes.subtitle || 'Create amazing experiences')}</div>
                            <div class="inline-block px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded">
                                ${this.escapeHtml(attributes.buttonText || 'Get Started')}
                            </div>
                        </div>
                    </div>
                `;
            }
            
            return '<div class="mt-2 text-xs text-gray-500">Block preview</div>';
        },

        /**
         * Update block indices
         */
        updateBlockIndices: function() {
            $('#nxw-blocks-container .block-item').each(function(index) {
                $(this).attr('data-block-index', index);
                $(this).find('input[name*="[name]"]').attr('name', 'blocks[' + index + '][name]');
                $(this).find('input[name*="[attributes]"]').attr('name', 'blocks[' + index + '][attributes]');
            });
        },

        /**
         * Update blocks counter
         */
        updateBlocksCounter: function() {
            var count = $('#nxw-blocks-container .block-item').length;
            $('.bg-blue-50.text-blue-700').text(count + ' ' + (count === 1 ? 'block' : 'blocks'));
        },

        /**
         * Update empty state
         */
        updateEmptyState: function() {
            var $container = $('#nxw-blocks-container');
            if ($container.find('.block-item').length === 0) {
                $container.html(`
                    <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                        </svg>
                        <h3 class="mt-4 text-sm font-medium text-gray-900">No blocks added yet</h3>
                        <p class="mt-2 text-sm text-gray-500">Click on a block from the sidebar to add it to this page.</p>
                    </div>
                `);
            }
        },

        /**
         * Escape HTML
         */
        escapeHtml: function(text) {
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        },

        /**
         * Handle form submit
         */
        handleFormSubmit: function(e) {
            // Form validation can be added here
            var $form = $(this);
            
            // Show loading state
            var $submitBtn = $form.find('button[type="submit"]');
            var originalText = $submitBtn.text();
            $submitBtn.prop('disabled', true).text(nxwPageBuilder.strings.saving || 'Saving...');
        },

        /**
         * Handle delete confirmation
         */
        handleDeleteConfirm: function(e) {
            if (!confirm(nxwPageBuilder.strings.confirmDelete)) {
                e.preventDefault();
                return false;
            }
        },

        /**
         * Show loader on button/element
         * Modular loader component - can be used anywhere
         * 
         * @param {jQuery} $element - jQuery element to show loader on
         * @param {Object} options - Optional configuration
         * @param {String} options.text - Custom loading text (default: 'Loading...')
         * @param {Boolean} options.disable - Disable element while loading (default: true)
         */
        showLoader: function($element, options) {
            options = options || {};
            var loadingText = options.text || 'Loading...';
            var disable = options.disable !== false; // Default to true
            
            // Store original state
            if (!$element.data('nxw-loader-original-html')) {
                $element.data('nxw-loader-original-html', $element.html());
            }
            
            var $buttonText = $element.find('.button-text');
            var $buttonLoader = $element.find('.button-loader');
            
            if ($buttonText.length && $buttonLoader.length) {
                // Use existing loader structure
                $buttonText.addClass('hidden');
                $buttonLoader.removeClass('hidden');
                $buttonLoader.find('.loader-text').text(loadingText);
            } else {
                // Create loader inline
                var originalHtml = $element.data('nxw-loader-original-html') || $element.html();
                var loaderHtml = '<svg class="animate-spin h-4 w-4 mr-2 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">' +
                    '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
                    '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>' +
                    '</svg>' +
                    '<span>' + this.escapeHtml(loadingText) + '</span>';
                $element.html(loaderHtml);
            }
            
            if (disable) {
                $element.prop('disabled', true);
            }
            
            $element.addClass('nxw-loading');
        },

        /**
         * Hide loader and restore original state
         * 
         * @param {jQuery} $element - jQuery element to hide loader on
         */
        hideLoader: function($element) {
            var originalHtml = $element.data('nxw-loader-original-html');
            
            var $buttonText = $element.find('.button-text');
            var $buttonLoader = $element.find('.button-loader');
            
            if ($buttonText.length && $buttonLoader.length) {
                // Use existing loader structure
                $buttonText.removeClass('hidden');
                $buttonLoader.addClass('hidden');
            } else if (originalHtml) {
                // Restore original HTML
                $element.html(originalHtml);
            }
            
            $element.prop('disabled', false);
            $element.removeClass('nxw-loading');
        },

        /**
         * Initialize unit indicators on page load
         */
        initUnitIndicators: function() {
            $('.nxw-size-input').each(function() {
                var $input = $(this);
                var value = $input.val().trim();
                var $indicator = $input.siblings('.unit-indicator');
                
                if (!value || !$indicator.length) {
                    return;
                }
                
                if (/rem$/i.test(value)) {
                    $indicator.text('rem');
                } else if (/px$/i.test(value)) {
                    $indicator.text('px');
                } else {
                    $indicator.text('rem');
                }
            });
        },

        /**
         * Auto-format size inputs (convert px to rem)
         * 
         * @param {jQuery} $form - Form element
         */
        autoFormatSizeInputs: function($form) {
            $form.find('.nxw-size-input').each(function() {
                var $input = $(this);
                var value = $input.val().trim();
                
                if (!value) {
                    return;
                }
                
                // Convert px to rem if needed
                if (/^[\d.]+px$/i.test(value)) {
                    var pxValue = parseFloat(value);
                    var remValue = (pxValue / 16).toFixed(3).replace(/\.?0+$/, '');
                    $input.val(remValue + 'rem');
                } else if (/^[\d.]+$/.test(value)) {
                    // Just a number, assume px and convert
                    var pxValue = parseFloat(value);
                    var remValue = (pxValue / 16).toFixed(3).replace(/\.?0+$/, '');
                    $input.val(remValue + 'rem');
                }
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        NXW.modules.admin.init();
    });

})(jQuery);

