<?php
declare(strict_types=1);

/**
 * Hero Block - Frontend Template
 * BEM naming convention: .hero__element--modifier
 *
 * @var array $attributes Block attributes
 */

$title = $attributes['title'] ?? 'Welcome to Our Website';
$subtitle = $attributes['subtitle'] ?? 'Create amazing experiences with our page builder';
$buttonText = $attributes['buttonText'] ?? 'Get Started';
$buttonLink = $attributes['buttonLink'] ?? '#';
$backgroundImage = $attributes['backgroundImage'] ?? '';
$backgroundColor = $attributes['backgroundColor'] ?? '#3b82f6';
$textColor = $attributes['textColor'] ?? '#ffffff';
$alignment = $attributes['alignment'] ?? 'center';
$padding = $attributes['padding'] ?? 'large';

// Build BEM classes dynamically - container class for width, hero classes for styling
$heroClasses = ['container', 'hero'];
if (!empty($alignment)) {
    $heroClasses[] = 'hero--align-' . esc_attr($alignment);
}
if (!empty($padding)) {
    $heroClasses[] = 'hero--padding-' . esc_attr($padding);
}

$heroStyle = [
    'background-color' => $backgroundColor,
    'color' => $textColor,
    'background-size' => 'cover',
    'background-position' => 'center',
    'background-repeat' => 'no-repeat',
];

if ($backgroundImage) {
    $heroStyle['background-image'] = 'url(' . esc_url($backgroundImage) . ')';
}

$styleString = '';
foreach ($heroStyle as $property => $value) {
    $styleString .= $property . ':' . $value . ';';
}
?>

<section class="<?php echo esc_attr(implode(' ', $heroClasses)); ?>" 
         style="<?php echo esc_attr($styleString); ?>"
         aria-label="<?php echo esc_attr($title ?: 'Hero section'); ?>">
    <div class="hero__container">
        <div class="hero__content">
            <?php if (!empty($title)): ?>
                <h1 class="hero__title">
                    <?php echo esc_html($title); ?>
                </h1>
            <?php endif; ?>
            
            <?php if (!empty($subtitle)): ?>
                <p class="hero__subtitle">
                    <?php echo esc_html($subtitle); ?>
                </p>
            <?php endif; ?>
            
            <?php if (!empty($buttonText) && !empty($buttonLink)): ?>
                <nav class="hero__actions" aria-label="Hero actions">
                    <a href="<?php echo esc_url($buttonLink); ?>" 
                       class="hero__button"
                       style="background-color: <?php echo esc_attr($textColor === '#ffffff' ? 'rgba(0,0,0,0.8)' : '#ffffff'); ?>; color: <?php echo esc_attr($textColor === '#ffffff' ? '#ffffff' : '#000000'); ?>;">
                        <?php echo esc_html($buttonText); ?>
                    </a>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</section>
