/**
 * Slider Component
 * Reusable image slider with auto-play
 */

import { qsa } from '../utils/dom.js';

// Constants
const DEFAULT_INTERVAL = 3000; // 3 seconds

export class Slider {
    /**
     * @param {Object} options
     * @param {string|Element} options.container - Slider container
     * @param {number} options.interval - Auto-slide interval in ms (default: 3000)
     * @param {boolean} options.autoPlay - Enable auto-play
     */
    constructor(options = {}) {
        this.container = typeof options.container === 'string' 
            ? document.getElementById(options.container) 
            : options.container;
        
        this.interval = options.interval || DEFAULT_INTERVAL;
        this.autoPlay = options.autoPlay !== false;
        this.currentIndex = 0;
        this.intervalId = null;
        
        this.slides = [];
        this.indicators = [];
        this.wrapper = null;
    }

    /**
     * Initialize slider
     */
    init() {
        if (!this.container) return this;

        this.slides = qsa('.slider-image, .slide', this.container);
        this.indicators = qsa('.slider-indicator, [class*="-indicator"], [class*="-dot"]', this.container);
        this.wrapper = this.container.querySelector('.slider-wrapper, [id*="wrapper"]');

        this.bindEvents();

        if (this.autoPlay && this.slides.length > 1) {
            this.start();
        }

        return this;
    }

    /**
     * Bind navigation events
     */
    bindEvents() {
        // Indicator clicks
        this.indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => this.goTo(index));
        });

        // Previous/Next buttons
        const prevBtn = this.container.querySelector('.slider-prev, [class*="-prev"]');
        const nextBtn = this.container.querySelector('.slider-next, [class*="-next"]');

        if (prevBtn) prevBtn.addEventListener('click', () => this.prev());
        if (nextBtn) nextBtn.addEventListener('click', () => this.next());

        // Touch swipe support for mobile
        this.setupTouchSwipe();
    }

    /**
     * Setup touch swipe for mobile slider navigation
     */
    setupTouchSwipe() {
        let touchStartX = 0;
        let touchEndX = 0;
        const minSwipeDistance = 50;

        this.container.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        this.container.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const swipeDistance = touchEndX - touchStartX;

            if (Math.abs(swipeDistance) >= minSwipeDistance) {
                if (swipeDistance > 0) {
                    // Swipe right - previous slide
                    this.prev();
                } else {
                    // Swipe left - next slide
                    this.next();
                }
            }
        }, { passive: true });
    }

    /**
     * Show specific slide
     * @param {number} index 
     */
    goTo(index) {
        this.currentIndex = index;
        this.update();
        this.resetInterval();
    }

    /**
     * Go to next slide
     */
    next() {
        this.currentIndex = (this.currentIndex + 1) % this.slides.length;
        this.update();
        this.resetInterval();
    }

    /**
     * Go to previous slide
     */
    prev() {
        this.currentIndex = (this.currentIndex - 1 + this.slides.length) % this.slides.length;
        this.update();
        this.resetInterval();
    }

    /**
     * Update slider display
     */
    update() {
        // For fade transition
        this.slides.forEach((slide, i) => {
            slide.style.opacity = i === this.currentIndex ? '1' : '0';
        });

        // For transform transition
        if (this.wrapper) {
            this.wrapper.style.transform = `translateX(-${this.currentIndex * 100}%)`;
        }

        // Update indicators
        this.indicators.forEach((indicator, i) => {
            const isActive = i === this.currentIndex;
            
            // Handle different indicator styles
            if (isActive) {
                indicator.classList.remove('w-2', 'w-3', 'bg-white/50');
                indicator.classList.add('w-4', 'w-6', 'bg-white');
            } else {
                indicator.classList.remove('w-4', 'w-6', 'bg-white');
                indicator.classList.add('w-2', 'w-3', 'bg-white/50');
            }
        });
    }

    /**
     * Start auto-play
     */
    start() {
        if (this.intervalId) return;
        this.intervalId = setInterval(() => this.next(), this.interval);
    }

    /**
     * Stop auto-play
     */
    stop() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
        }
    }

    /**
     * Reset interval (restart auto-play)
     */
    resetInterval() {
        if (this.autoPlay) {
            this.stop();
            this.start();
        }
    }
}

export default Slider;
