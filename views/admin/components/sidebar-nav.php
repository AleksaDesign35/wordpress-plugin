<?php
declare(strict_types=1);

/**
 * Sidebar Navigation Component
 * 
 * @var array $items Navigation items array with keys: id, label, url, icon (SVG path), active (bool)
 * @var string $baseUrl Base URL for navigation items (optional)
 */
?>

<div class="w-64 bg-gray-50 border-r border-gray-200 flex-shrink-0">
    <nav class="p-4 space-y-1">
        <?php foreach ($items as $item): 
            $isActive = $item['active'] ?? false;
            $itemId = $item['id'] ?? '';
            $itemLabel = $item['label'] ?? '';
            $itemUrl = $item['url'] ?? '#';
            $itemIcon = $item['icon'] ?? '';
        ?>
            <a href="<?php echo esc_url($itemUrl); ?>" 
               class="nxw-sidebar-nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 <?php echo $isActive ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-gray-700 hover:bg-gray-100'; ?>"
               data-nav-id="<?php echo esc_attr($itemId); ?>">
                <?php if (!empty($itemIcon)): ?>
                    <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <?php echo $itemIcon; ?>
                    </svg>
                <?php endif; ?>
                <span><?php echo esc_html($itemLabel); ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
</div>

