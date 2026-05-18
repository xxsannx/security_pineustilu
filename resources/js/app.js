/**
 * Main Application JavaScript
 * Core functionality loaded on all pages
 * 
 * This file is loaded globally via @vite(['resources/js/app.js'])
 * Page-specific JS should be loaded separately using @vite directive
 */

// Import global utilities (available for other modules)
import { onReady, qsa, qs } from './utils/dom.js';
import { clamp } from './utils/helpers.js';

// Make toggleAccordion available globally for inline onclick handlers
import { toggleAccordion } from './components/Accordion.js';
window.toggleAccordion = toggleAccordion;

/**
 * Global Lightbox State
 */
const lightboxState = {
    zoom: 1,
    minZoom: 0.5,
    maxZoom: 4,
    zoomStep: 0.25,
    isPanning: false,
    startX: 0,
    startY: 0,
    translateX: 0,
    translateY: 0
};

/**
 * Initialize global functionality
 */
function initGlobal() {
    // Add loaded class for CSS transitions
    document.documentElement.classList.add('js-loaded');

    // Setup smooth scroll for anchor links (basic)
    setupSmoothScroll();

    // Setup lazy loading for images without native support
    setupLazyLoading();

    // Setup global lightbox
    setupGlobalLightbox();
}

/**
 * Setup Global Lightbox Functions
 * These are called from inline onclick handlers in galeri-modal.blade.php
 */
function setupGlobalLightbox() {
    const modal = document.getElementById('lightbox-modal');
    const image = document.getElementById('lightbox-image');
    const container = document.getElementById('lightbox-container');
    const zoomLevel = document.getElementById('lightbox-zoom-level');

    if (!modal || !image) return;

    // Open lightbox function
    window.openLightbox = function(src, alt = '') {
        if (!modal || !image) return;
        
        // Reset state
        lightboxState.zoom = 1;
        lightboxState.translateX = 0;
        lightboxState.translateY = 0;
        
        image.src = src;
        image.alt = alt || 'Gallery Image';
        updateLightboxTransform();
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    // Close lightbox function
    window.closeLightbox = function() {
        if (!modal) return;
        
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        
        if (image) {
            image.src = '';
            image.style.transform = '';
        }
    };

    // Zoom in function
    window.zoomInLightbox = function() {
        lightboxState.zoom = clamp(lightboxState.zoom + lightboxState.zoomStep, lightboxState.minZoom, lightboxState.maxZoom);
        updateLightboxTransform();
    };

    // Zoom out function
    window.zoomOutLightbox = function() {
        lightboxState.zoom = clamp(lightboxState.zoom - lightboxState.zoomStep, lightboxState.minZoom, lightboxState.maxZoom);
        updateLightboxTransform();
    };

    // Reset zoom function
    window.resetLightboxZoom = function() {
        lightboxState.zoom = 1;
        lightboxState.translateX = 0;
        lightboxState.translateY = 0;
        updateLightboxTransform();
    };

    // Update transform helper
    function updateLightboxTransform() {
        if (image) {
            image.style.transform = `translate(${lightboxState.translateX}px, ${lightboxState.translateY}px) scale(${lightboxState.zoom})`;
        }
        if (zoomLevel) {
            zoomLevel.textContent = Math.round(lightboxState.zoom * 100) + '%';
        }
    }

    // Scroll to zoom
    if (container) {
        container.addEventListener('wheel', (e) => {
            e.preventDefault();
            const delta = e.deltaY > 0 ? -lightboxState.zoomStep : lightboxState.zoomStep;
            lightboxState.zoom = clamp(lightboxState.zoom + delta, lightboxState.minZoom, lightboxState.maxZoom);
            updateLightboxTransform();
        }, { passive: false });
    }

    // Pan with drag
    if (image) {
        image.addEventListener('mousedown', (e) => {
            if (lightboxState.zoom > 1) {
                lightboxState.isPanning = true;
                lightboxState.startX = e.clientX - lightboxState.translateX;
                lightboxState.startY = e.clientY - lightboxState.translateY;
                image.style.cursor = 'grabbing';
            }
        });

        document.addEventListener('mousemove', (e) => {
            if (lightboxState.isPanning) {
                lightboxState.translateX = e.clientX - lightboxState.startX;
                lightboxState.translateY = e.clientY - lightboxState.startY;
                updateLightboxTransform();
            }
        });

        document.addEventListener('mouseup', () => {
            lightboxState.isPanning = false;
            if (image) image.style.cursor = 'grab';
        });
    }

    // Close on backdrop click
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal || e.target === container) {
                window.closeLightbox();
            }
        });
    }

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            window.closeLightbox();
        }
    });
}

/**
 * Basic smooth scroll for anchor links
 */
function setupSmoothScroll() {
    qsa('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#' || href === '#top') {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }

            const target = qs(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
}

/**
 * Setup lazy loading fallback
 */
function setupLazyLoading() {
    // Modern browsers support native lazy loading
    if ('loading' in HTMLImageElement.prototype) {
        qsa('img[data-src]').forEach(img => {
            img.src = img.dataset.src;
        });
        return;
    }

    // Fallback for older browsers
    const lazyImages = qsa('img[data-src]');
    if (!lazyImages.length) return;

    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => imageObserver.observe(img));
}

// Initialize when DOM is ready
onReady(initGlobal);

// Export utilities for use in page-specific scripts if needed
export { onReady, qsa, qs } from './utils/dom.js';
export * from './utils/helpers.js';
