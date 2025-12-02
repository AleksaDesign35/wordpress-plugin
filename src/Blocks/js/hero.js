/**
 * Hero Block - Frontend JavaScript
 * BEM naming convention used in HTML
 */

(function() {
    'use strict';

    // Initialize hero blocks on page load
    document.addEventListener('DOMContentLoaded', function() {
        const heroBlocks = document.querySelectorAll('.nxw-hero[data-block="hero"]');
        
        heroBlocks.forEach(function(block) {
            // Add any frontend interactivity here
            const button = block.querySelector('.nxw-hero__button');
            
            if (button) {
                button.addEventListener('click', function(e) {
                    // Track button clicks or add analytics here if needed
                    console.log('Hero button clicked:', this.href);
                });
            }
        });
    });

})();
