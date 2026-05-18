/**
 * Dashboard Page JavaScript
 * Handles modals, hero video sound toggle, and wave divider animation
 */

import { Modal } from '../components/Modal.js';
import { onReady } from '../utils/dom.js';

class Dashboard {
    constructor() {
        this.modal = null;
        this.heroVideo = null;
        this.soundOn = null;
        this.soundOff = null;
    }

    init() {
        // Register modals manually with their IDs
        this.modal = new Modal();

        // Register specific modals for dashboard
        const modalIds = ['modalJabodetabek', 'modalLuarJawa', 'modalJawaTengahTimur', 'modalLuarNegeri'];
        
        modalIds.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                // Convert to camelCase key
                const key = id.replace('modal', '').replace(/^./, c => c.toLowerCase());
                this.modal.register(key, element);
            }
        });

        // Re-setup event listeners after manual registration
        this.setupEventListeners();
        
        // Initialize hero video sound toggle
        this.initHeroVideo();
        
        // Initialize wave divider animation
        this.initWaveDivider();
    }

    /**
     * Initialize hero video and sound toggle functionality
     */
    initHeroVideo() {
        this.heroVideo = document.getElementById('heroVideo');
        this.soundOn = document.getElementById('soundOn');
        this.soundOff = document.getElementById('soundOff');
        const soundToggle = document.getElementById('soundToggle');

        if (!this.heroVideo) return;

        // Ensure video starts muted (autoplay policy)
        this.heroVideo.muted = true;

        // Bind sound toggle button
        if (soundToggle) {
            soundToggle.addEventListener('click', () => this.toggleSound());
        }

        // Make toggleSound available globally for onclick attribute
        window.toggleSound = () => this.toggleSound();
    }

    /**
     * Toggle video sound on/off
     */
    toggleSound() {
        if (!this.heroVideo) return;

        this.heroVideo.muted = !this.heroVideo.muted;

        if (this.soundOn && this.soundOff) {
            if (this.heroVideo.muted) {
                this.soundOn.classList.add('hidden');
                this.soundOff.classList.remove('hidden');
            } else {
                this.soundOn.classList.remove('hidden');
                this.soundOff.classList.add('hidden');
            }
        }
    }

    /**
     * Initialize wave divider animation
     * Synced with navbar visibility via custom event and scroll fallback
     */
    initWaveDivider() {
        const waveDivider = document.getElementById('waveDivider');
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

    setupEventListeners() {
        // Open modal listeners
        document.querySelectorAll('[data-open-modal]').forEach(button => {
            button.addEventListener('click', () => {
                const modalType = button.getAttribute('data-open-modal');
                this.modal.open(modalType);
            });
        });

        // Close modal listeners
        document.querySelectorAll('[data-close-modal]').forEach(button => {
            button.addEventListener('click', () => {
                const modalType = button.getAttribute('data-close-modal');
                this.modal.close(modalType);
            });
        });

        // Close on backdrop click
        Object.values(this.modal.modals).forEach(modal => {
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        // Find the key for this modal
                        const key = Object.keys(this.modal.modals).find(
                            k => this.modal.modals[k] === modal
                        );
                        if (key) this.modal.close(key);
                    }
                });
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.modal.closeAll();
            }
        });
    }
}

// Initialize on DOM ready
onReady(() => {
    const dashboard = new Dashboard();
    dashboard.init();
});

export default Dashboard;
