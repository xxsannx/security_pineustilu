/**
 * Area Page JavaScript
 * Handles accordion toggles, gallery sliders, and zoomable modals
 */

import { Accordion } from '../components/Accordion.js';
import { onReady, qsa } from '../utils/dom.js';
import { clamp, getTouchDistance } from '../utils/helpers.js';

// Constants
const SLIDER_INTERVAL = 3000; // 3 seconds
const MIN_ZOOM = 0.5;
const MAX_ZOOM = 3;
const ZOOM_STEP = 0.25;

class Area {
    constructor() {
        this.accordion = null;
        this.currentModal = null;
        this.currentZoom = 1;
        this.gallerySliders = new Map();
        this.heroCarousels = new Map();
    }

    init() {
        this.accordion = new Accordion().init();
        this.initHeroCarousels();
        this.initGallerySliders();
        this.setupZoomableModals();
    }

    /**
     * Initialize all gallery sliders on the page
     */
    initGallerySliders() {
        qsa('.gallery-slider').forEach(container => {
            const sliderId = container.id || `gallery-slider-${Math.random().toString(36).substring(2, 11)}`;
            const wrapper = container.querySelector('.slider-wrapper');
            const slides = wrapper ? wrapper.children : [];
            const prevBtn = container.querySelector('.slider-prev');
            const nextBtn = container.querySelector('.slider-next');
            const dots = qsa('.slider-dot', container);
            
            if (slides.length <= 1) return;

            const slider = {
                currentIndex: 0,
                totalSlides: slides.length,
                wrapper,
                dots,
                intervalId: null,
                container
            };

            // Navigation buttons
            prevBtn?.addEventListener('click', () => {
                slider.currentIndex = (slider.currentIndex - 1 + slider.totalSlides) % slider.totalSlides;
                this.updateGallerySlider(slider);
                this.resetSliderInterval(slider);
            });

            nextBtn?.addEventListener('click', () => {
                slider.currentIndex = (slider.currentIndex + 1) % slider.totalSlides;
                this.updateGallerySlider(slider);
                this.resetSliderInterval(slider);
            });

            // Dot indicators
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    slider.currentIndex = index;
                    this.updateGallerySlider(slider);
                    this.resetSliderInterval(slider);
                });
            });

            // Touch swipe support for mobile
            this.setupTouchSwipe(slider);

            // Start auto-play
            this.startSliderInterval(slider);
            this.gallerySliders.set(sliderId, slider);
        });
    }

    /**
     * Setup touch swipe for mobile slider navigation
     */
    setupTouchSwipe(slider) {
        let touchStartX = 0;
        let touchEndX = 0;
        const minSwipeDistance = 50;

        slider.container.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        slider.container.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const swipeDistance = touchEndX - touchStartX;

            if (Math.abs(swipeDistance) >= minSwipeDistance) {
                if (swipeDistance > 0) {
                    // Swipe right - previous slide
                    slider.currentIndex = (slider.currentIndex - 1 + slider.totalSlides) % slider.totalSlides;
                } else {
                    // Swipe left - next slide
                    slider.currentIndex = (slider.currentIndex + 1) % slider.totalSlides;
                }
                this.updateGallerySlider(slider);
                this.resetSliderInterval(slider);
            }
        }, { passive: true });
    }

    /**
     * Update gallery slider display
     */
    updateGallerySlider(slider) {
        if (slider.wrapper) {
            slider.wrapper.style.transform = `translateX(-${slider.currentIndex * 100}%)`;
        }

        slider.dots.forEach((dot, index) => {
            const isActive = index === slider.currentIndex;
            dot.classList.toggle('bg-white', isActive);
            dot.classList.toggle('w-6', isActive);
            dot.classList.toggle('bg-white/50', !isActive);
            dot.classList.toggle('w-3', !isActive);
        });
    }

    /**
     * Start slider auto-play interval
     */
    startSliderInterval(slider) {
        slider.intervalId = setInterval(() => {
            slider.currentIndex = (slider.currentIndex + 1) % slider.totalSlides;
            this.updateGallerySlider(slider);
        }, SLIDER_INTERVAL);
    }

    /**
     * Reset slider interval on user interaction
     */
    resetSliderInterval(slider) {
        if (slider.intervalId) {
            clearInterval(slider.intervalId);
        }
        this.startSliderInterval(slider);
    }

    /**
     * Initialize all hero carousels on the page
     */
    initHeroCarousels() {
        qsa('.hero-carousel').forEach(container => {
            const track = container.querySelector('.hero-carousel-track');
            const slides = track ? track.children : [];
            
            if (slides.length <= 1) return;

            const autoplayDelay = parseInt(container.dataset.autoplay) || 3000;
            const indicatorContainer = container.parentElement?.querySelector('.hero-carousel-indicators');
            const dots = indicatorContainer ? qsa('.hero-indicator-dot', indicatorContainer) : [];
            
            const carousel = {
                currentIndex: 0,
                totalSlides: slides.length,
                track,
                container,
                dots,
                intervalId: null,
                autoplayDelay
            };

            // Initial dot state
            this.updateHeroCarousel(carousel);
            this.startHeroInterval(carousel);
            this.heroCarousels.set(container, carousel);
        });
    }

    /**
     * Update hero carousel display and indicators
     */
    updateHeroCarousel(carousel) {
        if (carousel.track) {
            carousel.track.style.transform = `translateX(-${carousel.currentIndex * 100}%)`;
        }
        // Update dot indicators
        carousel.dots?.forEach((dot, index) => {
            if (index === carousel.currentIndex) {
                dot.classList.add('bg-white', 'w-6');
                dot.classList.remove('bg-white/50', 'w-2');
            } else {
                dot.classList.remove('bg-white', 'w-6');
                dot.classList.add('bg-white/50', 'w-2');
            }
        });
    }

    /**
     * Start hero carousel auto-play interval
     */
    startHeroInterval(carousel) {
        carousel.intervalId = setInterval(() => {
            carousel.currentIndex = (carousel.currentIndex + 1) % carousel.totalSlides;
            this.updateHeroCarousel(carousel);
        }, carousel.autoplayDelay);
    }

    setupZoomableModals() {
        // Find all zoomable images/modals
        qsa('[data-zoomable]').forEach(trigger => {
            trigger.addEventListener('click', () => {
                const src = trigger.dataset.src || trigger.src;
                const alt = trigger.alt || '';
                this.openZoomModal(src, alt);
            });
        });
    }

    openZoomModal(src, alt) {
        // Create modal if not exists
        if (!this.currentModal) {
            this.createZoomModal();
        }

        const img = this.currentModal.querySelector('img');
        img.src = src;
        img.alt = alt;
        this.currentZoom = 1;
        this.updateZoom();

        this.currentModal.classList.remove('hidden');
        this.currentModal.style.display = 'flex';
    }

    createZoomModal() {
        this.currentModal = document.createElement('div');
        this.currentModal.className = 'fixed inset-0 bg-black/80 z-50 hidden items-center justify-center';
        this.currentModal.innerHTML = `
            <div class="relative max-w-[90vw] max-h-[85vh]">
                <img class="max-w-full max-h-[85vh] object-contain transition-transform duration-200" />
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                    <button class="zoom-out px-3 py-1 bg-white/90 rounded-lg text-lg font-bold">−</button>
                    <button class="zoom-reset px-3 py-1 bg-white/90 rounded-lg text-sm">Reset</button>
                    <button class="zoom-in px-3 py-1 bg-white/90 rounded-lg text-lg font-bold">+</button>
                </div>
                <button class="zoom-close absolute top-4 right-4 w-10 h-10 bg-white/90 rounded-full text-xl">×</button>
            </div>
        `;

        document.body.appendChild(this.currentModal);

        // Bind events
        this.currentModal.querySelector('.zoom-in').addEventListener('click', () => this.zoomIn());
        this.currentModal.querySelector('.zoom-out').addEventListener('click', () => this.zoomOut());
        this.currentModal.querySelector('.zoom-reset').addEventListener('click', () => this.zoomReset());
        this.currentModal.querySelector('.zoom-close').addEventListener('click', () => this.closeZoomModal());
        this.currentModal.addEventListener('click', (e) => {
            if (e.target === this.currentModal) this.closeZoomModal();
        });

        // Keyboard events
        document.addEventListener('keydown', (e) => {
            if (!this.currentModal.classList.contains('hidden')) {
                if (e.key === 'Escape') this.closeZoomModal();
                if (e.key === '+' || e.key === '=') this.zoomIn();
                if (e.key === '-') this.zoomOut();
            }
        });

        // Touch pinch zoom
        this.setupPinchZoom();
    }

    setupPinchZoom() {
        const img = this.currentModal.querySelector('img');
        let initialDistance = 0;
        let initialZoom = 1;

        img.addEventListener('touchstart', (e) => {
            if (e.touches.length === 2) {
                initialDistance = getTouchDistance(e.touches);
                initialZoom = this.currentZoom;
            }
        });

        img.addEventListener('touchmove', (e) => {
            if (e.touches.length === 2) {
                e.preventDefault();
                const currentDistance = getTouchDistance(e.touches);
                const scale = currentDistance / initialDistance;
                this.currentZoom = clamp(initialZoom * scale, MIN_ZOOM, MAX_ZOOM);
                this.updateZoom();
            }
        });
    }

    zoomIn() {
        this.currentZoom = clamp(this.currentZoom + ZOOM_STEP, MIN_ZOOM, MAX_ZOOM);
        this.updateZoom();
    }

    zoomOut() {
        this.currentZoom = clamp(this.currentZoom - ZOOM_STEP, MIN_ZOOM, MAX_ZOOM);
        this.updateZoom();
    }

    zoomReset() {
        this.currentZoom = 1;
        this.updateZoom();
    }

    updateZoom() {
        const img = this.currentModal.querySelector('img');
        img.style.transform = `scale(${this.currentZoom})`;
    }

    closeZoomModal() {
        this.currentModal.classList.add('hidden');
        this.currentModal.style.display = 'none';
    }
}

// Initialize on DOM ready
onReady(() => {
    const area = new Area();
    area.init();
});

export default Area;
