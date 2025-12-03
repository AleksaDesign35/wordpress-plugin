<?php
declare(strict_types=1);

/**
 * Admin Layout Template
 *
 * @var string $title
 * @var string $content
 */
?>

<div class="nxw-page-builder-wrap">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight"><?php echo esc_html($title); ?></h1>
                    <?php if (isset($subtitle)): ?>
                        <p class="mt-2 text-sm text-gray-500"><?php echo esc_html($subtitle); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Content -->
        <?php if (isset($noWrapper) && $noWrapper): ?>
            <?php echo $content; // Already escaped in views ?>
        <?php else: ?>
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                <?php echo $content; // Already escaped in views ?>
            </div>
        <?php endif; ?>
    </div>
</div>

