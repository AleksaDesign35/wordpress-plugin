<?php
declare(strict_types=1);

use NXW\PageBuilder\Helpers\BlockHelper;
use NXW\PageBuilder\Blocks\BlockDefaults;

/**
 * Feature Block - Frontend Template
 * BEM naming convention: .nxw-feature__element--modifier
 *
 * @param array $block Block data with attributes
 * @return void
 */
function nxw_display_feature_block(array $block): void
{
    if (empty($block)) {
        return;
    }

    $settings = BlockHelper::extractSettings($block);
    $title = $settings['title'] ?? BlockDefaults::getFeatureTitle();
    $features = $settings['features'] ?? [];
    
    if (empty($features)) {
        $features = BlockDefaults::getFeatureDefaults();
    }

    $sectionClasses = BlockHelper::buildSectionClasses('feature', $settings);
    $sectionStyles = BlockHelper::buildSectionStyles($settings);
    ?>

    <section class="<?= $sectionClasses; ?>"<?= $sectionStyles; ?> aria-label="Feature section">
        <div class="container">
         <div class="nxw-feature__container">
            <?php if (!empty($title)): ?>
                <h2 class="nxw-feature__title">
                    <?= esc_html($title); ?>
                </h2>
            <?php endif; ?>
            
            <?php if (!empty($features)): ?>
                <div class="nxw-feature__items">
                    <?php foreach ($features as $feature): 
                        echo nxw_render_feature_item($feature);
                    endforeach; ?>
                </div>
            <?php endif; ?>
         </div>
    </section>
    <?php
}

/**
 * Render single feature item
 * 
 * @param array $feature Feature data
 * @return string Rendered HTML
 */
function nxw_render_feature_item(array $feature): string {
    $number = esc_html($feature['number'] ?? '');
    $iconUrl = esc_url($feature['icon'] ?? '');
    $featureTitle = esc_html($feature['title'] ?? '');
    $link = esc_url($feature['link'] ?? '#');
    $description = esc_html($feature['description'] ?? '');
    $isActive = isset($feature['isActive']) && $feature['isActive'] === true;
    
    $cardClasses = ['nxw-feature__card'];
    if ($isActive) {
        $cardClasses[] = 'nxw-feature__card--active';
    }
    
    $hasLink = !empty($link) && $link !== '#';
    $iconAlt = !empty($featureTitle) ? esc_attr($featureTitle . ' icon') : 'Feature icon';
    
    ob_start();
    ?>
    <div class="nxw-feature__item">
        <article class="<?= esc_attr(implode(' ', $cardClasses)); ?>">
            <?php if (!empty($number)): ?>
                <div class="nxw-feature__number">
                    <span class="nxw-feature__number"><?= $number; ?></span>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($iconUrl)): ?>
                <div class="nxw-feature__icon">
                    <img src="<?= $iconUrl; ?>" alt="<?= $iconAlt; ?>">
                </div>
            <?php endif; ?>
            
            <div class="nxw-feature__content">
                <?php if (!empty($featureTitle)): ?>
                    <h3 class="nxw-feature__heading">
                        <?php if ($hasLink): ?>
                            <a href="<?= $link; ?>" class="nxw-feature__link">
                                <?= $featureTitle; ?>
                            </a>
                        <?php else: ?>
                            <?= $featureTitle; ?>
                        <?php endif; ?>
                    </h3>
                <?php endif; ?>
                
                <?php if (!empty($description)): ?>
                    <p class="nxw-feature__description">
                        <?= $description; ?>
                    </p>
                <?php endif; ?>
            </div>
        </article>
    </div>
    <?php
    return ob_get_clean();
}

if (isset($attributes)) {
    nxw_display_feature_block(['attributes' => $attributes, 'settings' => $attributes]);
}
