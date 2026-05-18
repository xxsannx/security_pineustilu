/**
 * Morikafe Page JavaScript
 * Handles hero slider, gallery slider, and wave divider animation
 */

import { Slider } from '../components/Slider.js';
import { onReady, qsa } from '../utils/dom.js';

// Constants
const SLIDER_INTERVAL = 3000; // 3 seconds for all sliders

class Morikafe {
    constructor() {
        this.heroSlider = null;
        this.gallerySlider = null;
    }

    init() {
        this.initHeroSlider();
        this.initGallerySlider();
        this.initWaveDivider();
        this.initSmoothScroll();
    }

    /**
     * Initialize hero image slider
     */
    initHeroSlider() {
        const container = document.getElementById('morikafe-slider');
        if (!container) return;

        this.heroSlider = new Slider({
            container,
            interval: SLIDER_INTERVAL,
            autoPlay: true
        }).init();

        // Override update method to handle morikafe indicators
        const indicators = document.querySelectorAll('.morikafe-indicator');
        const originalUpdate = this.heroSlider.update.bind(this.heroSlider);
        
        this.heroSlider.update = () => {
            originalUpdate();
            this.updateIndicators(indicators, this.heroSlider.currentIndex);
        };

        // Make global function available for onclick indicators
        window.setMorikafeSlide = (index) => {
            this.heroSlider.goTo(index);
        };
    }

    /**
     * Update indicator styles based on current slide
     */
    updateIndicators(indicators, currentIndex) {
        indicators.forEach((indicator, i) => {
            const isActive = i === currentIndex;
            indicator.classList.toggle('w-4', isActive);
            indicator.classList.toggle('bg-white', isActive);
            indicator.classList.toggle('w-2', !isActive);
            indicator.classList.toggle('bg-white/50', !isActive);
        });
    }

    /**
     * Initialize gallery & meeting room slider
     */
    initGallerySlider() {
        const container = document.getElementById('gallery-meeting-slider');
        if (!container) return;

        const wrapper = container.querySelector('#slider-wrapper');
        const slides = wrapper ? wrapper.children : [];
        const prevBtn = container.querySelector('.morikafe-prev');
        const nextBtn = container.querySelector('.morikafe-next');
        const dots = qsa('.morikafe-dot', container);

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
        this.gallerySlider = slider;
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
     * Reset slider interval (on user interaction)
     */
    resetSliderInterval(slider) {
        if (slider.intervalId) {
            clearInterval(slider.intervalId);
        }
        this.startSliderInterval(slider);
    }

    /**
     * Initialize wave divider animation
     * Synced with navbar visibility via custom event and scroll fallback
     */
    initWaveDivider() {
        const waveDivider = document.getElementById('waveDividerMorikafe');
        if (!waveDivider) return;

        const SCROLL_THRESHOLD = 50;

        /**
         * Show wave divider with animation
         */
        const showWaveDivider = () => {
            waveDivider.classList.remove('translate-y-full', 'opacity-0');
            waveDivider.classList.add('translate-y-0', 'opacity-100');
        };

        /**
         * Hide wave divider with animation
         */
        const hideWaveDivider = () => {
            waveDivider.classList.remove('translate-y-0', 'opacity-100');
            waveDivider.classList.add('translate-y-full', 'opacity-0');
        };

        /**
         * Update based on scroll position
         */
        const updateFromScroll = () => {
            if (window.scrollY > SCROLL_THRESHOLD) {
                showWaveDivider();
            } else {
                hideWaveDivider();
            }
        };

        // Listen for navbar visibility changes (dispatched from navbar-menu.js)
        window.addEventListener('navbarVisibilityChange', (e) => {
            if (e.detail.visible) {
                showWaveDivider();
            } else {
                hideWaveDivider();
            }
        });

        // Also listen for scroll as fallback (in case event not received)
        window.addEventListener('scroll', updateFromScroll, { passive: true });

        // Initial state based on current scroll position
        updateFromScroll();
    }

    /**
     * Initialize smooth scroll for anchor links
     */
    initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const target = document.querySelector(targetId);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }
}

// Initialize on DOM ready
onReady(() => {
    const morikafe = new Morikafe();
    morikafe.init();
});

export default Morikafe;
